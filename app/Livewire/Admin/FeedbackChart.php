<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FeedbackRatingType;
use Illuminate\Support\Facades\DB;
use App\Models\Feedback;
use App\Models\FeedbackRating;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;


class FeedbackChart extends Component
{

    public $chartData = [];
    public $comments = [];
    public $rating_name;
    public $feedbackRatingTypes;
    public $transactionNumber;

    //Modals for deleting
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public $createRatingTypeModal;

    public function render()
    {
        return view('livewire.admin.feedback-chart', [
            'feedback_rating_types' => $this->feedbackRatingTypes,
        ]);
    }

    public function mount()
    {
        $this->loadComments();
        $this->loadChartData();
        $this->feedbackRatingTypes = FeedbackRatingType::all();
    }

    public function loadChartData()
    {
        // Get average rating value for each rating type
        $this->chartData = DB::table('trn_feedback_ratings')
            ->join('trn_feedback_rating_types', 'trn_feedback_ratings.rating_type_id', '=', 'trn_feedback_rating_types.id')
            ->select(
                'trn_feedback_rating_types.rating_name',
                DB::raw('AVG(trn_feedback_ratings.rating_value) as average_rating')
            )
            ->groupBy('trn_feedback_rating_types.rating_name')
            ->get();
    }

    public function loadComments($transactionNumber = null)
    {
        $query = Feedback::with
        ('transaction.transactionUser',
        'feedbackRatings.ratingType')
            ->whereNotNull('comments')
            ->orderBy('submitted_at', 'desc');

        if ($transactionNumber) {
            $query->where('transaction_number', $transactionNumber);
        }

        $this->comments = $query->take(10)
        ->get();
    }

    public function approveComment($id)
    {
        $feedback = Feedback::find($id);

        if ($feedback) {
            $feedback->status = 'approved';
            $feedback->save();
            $this->loadComments();
            session()->flash('message', 'Comment approved successfully.');
        }
    }

    public function rejectComment($id)
    {
        $feedback = Feedback::find($id);

        if ($feedback) {
            $feedback->status = 'rejected';
            $feedback->save();
            $this->loadComments();
            session()->flash('message', 'Comment rejected successfully.');
        }
    }

    public function openRatingTypeModal()
    {

        Log::info('Open Rating Type method called.');
        $this->createRatingTypeModal = true;
    }

    public function CloseRatingTypeModal()
    {

        Log::info('Close Rating Type method called.');
        $this->createRatingTypeModal = false;
    }

    public function CreateRatingType()
    {
        Log::info('Add Rating Type method called.');

        // Validate the input data
        $this->validate([
            'rating_name' => 'required|string',
        ]);

        // Create the payment record
        FeedbackRatingType::create([
            'rating_name' => $this->rating_name,
        ]);

        // Reset the form fields after successful creation
        $this->reset([
            'rating_name',
        ]);

        // Redirect to the same reservation view to refresh data
        return redirect()->route('admin.feedback')
            ->with('success', 'Feedback created successfully.');
    }

    public function RemoveRatingType()
    {
        $ratingType = FeedbackRatingType::find($this->confirmItemDelete);

        if (!$ratingType) {
            session()->flash('error', 'Rating Type not found.');
            return;
        }

        //If rating is used in feedback, do not delete
        if (FeedbackRating::where('rating_type_id', $ratingType->id)->exists()) {
            $this->cannotDeleteItem = true; // Show the cannot delete modal
            $this->confirmItemDelete = null; // Close the confirmation modal
            return;
        }

        try{
            $ratingType->delete(); // Attempt soft deletion

            // Reset confirmation modal
            $this->confirmItemDelete = null;

            //Re-fetch rating types
            $this->feedbackRatingTypes = FeedbackRatingType::all();

            // Flash success message
            session()->flash('message', 'Rating Type successfully deleted!');
            }catch (QueryException $e) {
                // Check if the error is an integrity constraint violation
                if ($e->getCode() == 23000) {
                // $this->cannotDeleteItem = true; // Show the cannot delete modal
                } else {
                    throw $e; // Re-throw other exceptions
                }
            }
    }
}




    // public function loadChartData()
    // {
    //     $ratingTypes = FeedbackRatingType::all();

    //     foreach ($ratingTypes as $type) {
    //         $data = DB::table('trn_feedback_ratings')
    //             ->join('trn_feedback', 'trn_feedback.id', '=', 'trn_feedback_ratings.feedback_id')
    //             ->select(DB::raw('DATE(trn_feedback.submitted_at) as date'), DB::raw('AVG(rating_value) as avg_rating'))
    //             ->where('rating_type_id', $type->id)
    //             ->groupBy('date')
    //             ->orderBy('date')
    //             ->get();

    //         $this->chartData[] = [
    //             [
    //                 'label' => 'Cleanliness',
    //                 'data' => [4.5, 4.3, 4.7],
    //                 'dates' => ['2025-05-01', '2025-05-02', '2025-05-03']
    //             ]
    //         ];
    //     }
    // }
