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

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->propertyName = Property::find($propertyId)->name_number ?? 'Property';
        $this->loadPropertyReviewsSummary();
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
                        'overall_rating' => $overallRating ? $overallRating->rating_value : 0,
                        'ratings' => $feedback->feedbackRatings->map(function ($rating) {
                            return [
                                'type' => $rating->ratingType->rating_name,
                                'value' => $rating->rating_value
                            ];
                        })->toArray()
                    ];
                });
        }

        $this->showReviewsModal = true;
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