<?php

namespace App\Livewire\Guest\Reservation;

use Livewire\Component;
use App\Models\Property;
use App\Models\Feedback;
use App\Models\FeedbackRating;
use App\Models\TransactionProperty;
use Carbon\Carbon;

class PropertyReviews extends Component
{
    public $propertyId;
    public $showReviewsModal = false;
    public $propertyReviews = [];
    public $averageRating = 0;
    public $totalReviews = 0;
    public $ratingBreakdown = [];
    public $propertyName;
    public $liked = []; 
    public $disliked = [];

    public $reviewSort = 'newest'; //For sorting
    

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->propertyName = Property::find($propertyId)->name_number ?? 'Property';
        $this->loadPropertyReviewsSummary();

        //For comment likes session
        $this->liked = session()->get('liked_reviews', []);
    }

    public function loadPropertyReviewsSummary()
    {
        // Get all transactions for this property
        $propertyTransactions = TransactionProperty::where('property_id', $this->propertyId)
            ->pluck('transaction_id');

        if ($propertyTransactions->count() > 0) {
            // Get approved feedback for these transactions
            $feedbacks = Feedback::whereIn('transaction_id', $propertyTransactions)
                ->where('status', 'approved')
                ->with(['feedbackRatings.ratingType', 'user'])
                ->select('trn_feedback.*')
                ->get();

           
            $this->totalReviews = $feedbacks->count();
            
            if ($this->totalReviews > 0) {
                // Calculate average rating
                $totalRating = 0;
                $ratingCount = 0;
                $breakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

                foreach ($feedbacks as $feedback) {
                    foreach ($feedback->feedbackRatings as $rating) {
                        if ($rating->ratingType->rating_name === 'Overall Experience') {
                            $totalRating += $rating->rating_value;
                            $ratingCount++;
                            
                            // Count rating breakdown
                            $roundedRating = round($rating->rating_value);
                            if (isset($breakdown[$roundedRating])) {
                                $breakdown[$roundedRating]++;
                            }
                        }
                    }
                }

                $this->averageRating = $ratingCount > 0 ? round($totalRating / $ratingCount, 1) : 0;
                $this->ratingBreakdown = $breakdown;
            }
        }
    }

    // ----------------- LIKES COUNTER ------------------ // --di pa gumagana session store of liked mark
    public function getLikesCount($feedbackId){

        $liked = session()->get('liked_reviews', []);

        $feedback = Feedback::find($feedbackId);

        if (!$feedback){
            return;
        }

        //If review is already liked, allow unlike 
        if (in_array($feedbackId, $liked)){
             if ($feedback->feedback_likes > 0) {
            $feedback->decrement('feedback_likes');
        }

         // Remove from liked list
        $liked = array_diff($liked, [$feedbackId]);

        session()->put('liked_reviews', $liked);
        $this->liked = $liked;

        // Reload UI
        $this->showReviews();
        return;
        }
        //Else, proceed to like

         $feedback->increment('feedback_likes');

        // Add to liked list
        $liked[] = $feedbackId;
        session()->put('liked_reviews', $liked);

        // Update Livewire state
        $this->liked = $liked;

        // Refresh list
        $this->showReviews();
        
    }

    public function getDislikesCount($feedbackId){
        $disliked = session()->get('disliked_reviews', []);

        //Prevent multiple liking in one session
        if (in_array($feedbackId, $this->liked)) {
        return; // Already liked; do nothing
        }

        $feedback = Feedback::find($feedbackId);
        if ($feedback) {
            $feedback->increment('feedback_dislikes');
        }

        $disliked[] = $feedbackId;
        session()->put('disliked_reviews', $disliked);

        //Update session handler
        $this->disliked = $disliked;;

        //Reload the reviews summary to update likes count
        $this->showReviews(); 
    }


    // ------------------ Sort Reviews ------------------ //
    public function updatedReviewSort()
    {
        $this->sortReviews();
    }

    public function sortReviews(){
        // Must be a collection, not empty
    if (!$this->propertyReviews || !count($this->propertyReviews)) {
        return;
    }

    $collection = collect($this->propertyReviews);

    switch ($this->reviewSort) {

        case 'likes':
            // Sort by number of likes (high to low)
            $sorted = $collection->sortByDesc(function ($review) {
                return $review['feedback_likes'] ?? 0;
            });
            break;

        case 'stars':
            // Sort by overall rating (high to low)
            $sorted = $collection->sortByDesc(function ($review) {
                return $review['overall_rating'] ?? 0;
            });
            break;

        case 'newest':
        default:
            // Sort by submitted_at (latest → oldest)
            $sorted = $collection->sortByDesc(function ($review) {
                return $review['submitted_at']
                    ? Carbon::parse($review['submitted_at'])
                    : null;
            });
            break;
    }

    // Reassign the sorted list back
    $this->propertyReviews = $sorted->values();
    }

    // ------------------ Show Reviews Modal ------------------ //
    public function showReviews()
    {
        // Get all transactions for this property
        $propertyTransactions = TransactionProperty::where('property_id', $this->propertyId)
            ->pluck('transaction_id');

        if ($propertyTransactions->count() > 0) {
            // Get approved feedback with ratings and user info
            $this->propertyReviews = Feedback::whereIn('transaction_id', $propertyTransactions)
                ->where('status', 'approved')
                ->with([
                    'feedbackRatings.ratingType',
                    'user'
                ])
                ->orderBy('submitted_at', 'desc')
                ->get()
                ->map(function ($feedback) {
                    $overallRating = $feedback->feedbackRatings->firstWhere('ratingType.rating_name', 'Overall Experience');
                    
                    // Handle date formatting safely
                    $submittedAt = $feedback->submitted_at;
                    $dateFormatted = 'No date';
                    
                    if ($submittedAt) {
                        try {
                            if (is_string($submittedAt)) {
                                $dateFormatted = Carbon::parse($submittedAt)->format('M d, Y');
                            } elseif ($submittedAt instanceof \Carbon\Carbon) {
                                $dateFormatted = $submittedAt->format('M d, Y');
                            }
                        } catch (\Exception $e) {
                            $dateFormatted = 'Invalid date';
                        }
                    }

                    // Get user name with fallback
                    $user = $feedback->user;
                    $guestName = 'Anonymous Guest';
                    
                    if ($user) {
                        $nameParts = [
                            $user->first_name,
                            $user->middle_name,
                            $user->last_name,
                            $user->suffix
                        ];
                        
                        $fullName = implode(' ', array_filter($nameParts));
                        $guestName = !empty(trim($fullName)) ? $fullName : 'Anonymous Guest';
                    }
                    
                    return [
                        'id' => $feedback->id,
                        'guest_name' => $guestName,
                        'comments' => $feedback->comments,
                        'submitted_at' => $submittedAt,
                        'date_formatted' => $dateFormatted,
                        'feedback_likes' => $feedback->feedback_likes,
                        'feedback_dislikes' => $feedback->feedback_dislikes,
                        'overall_rating' => $overallRating ? $overallRating->rating_value : 0,
                        'ratings' => $feedback->feedbackRatings->map(function ($rating) {
                            return [
                                'type' => $rating->ratingType->rating_name,
                                'value' => $rating->rating_value
                            ];
                        })->toArray()
                    ];
                });

        $this->showReviewsModal = true;
        }

        $this->liked = session()->get('liked_reviews', []);
    }

    public function closeReviews()
    {
        $this->showReviewsModal = false;
        $this->propertyReviews = [];
    }

    public function render()
    {
        return view('livewire.guest.reservation.property-reviews');
    }
}