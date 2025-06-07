<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\FeedbackRatingType;
use Illuminate\Support\Facades\DB;
use App\Models\Feedback;
use Illuminate\Support\Facades\Log;


class FeedbackChart extends Component
{

    public $chartData = [];
    public $comments = [];
    public $rating_name;
    public $feedbackRatingTypes;

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

    public function loadComments()
    {
        $this->comments = Feedback::with('transaction.transactionUser') // Eager load relationships
            ->whereNotNull('comments')
            ->orderBy('submitted_at', 'desc')
            ->take(10)
            ->get();
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

    public function RemoveRatingType($id)
    {
        Log::info('Remove Rating Type method called.');

        FeedbackRatingType::findOrFail($id)->delete();
        $this->feedbackRatingTypes = FeedbackRatingType::all();
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