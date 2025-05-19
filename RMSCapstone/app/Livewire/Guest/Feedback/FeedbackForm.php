<?php

namespace App\Livewire\Guest\Feedback;

use Livewire\Component;
use App\Models\Feedback;
use App\Models\FeedbackRating;
use App\Models\FeedbackRatingType;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FeedbackForm extends Component
{

    public $transactionId;
    public $comments;
    public $ratingValues = [];

    public $ratingTypes;

    public function mount()
    {
        $this->ratingTypes = FeedbackRatingType::all();
    }

    public function submit()
    {
        $expectedRatingTypeIds = FeedbackRatingType::pluck('id')->toArray();

        $rules = [
            'transactionId' => 'required|numeric',
            'comments' => 'nullable|string',
            'ratingValues' => 'required|array',
        ];

        foreach ($expectedRatingTypeIds as $id) {
            $rules["ratingValues.$id"] = 'required|numeric|min:1|max:5';
        }

        $this->validate($rules);

        // Create feedback
        $feedback = Feedback::create([
            'transaction_id' => $this->transactionId,
            'submitted_at' => now(),
            'comments' => $this->comments,
        ]);

        foreach ($this->ratingValues as $typeId => $value) {
            FeedbackRating::create([
                'feedback_id' => $feedback->id,
                'rating_type_id' => $typeId,
                'rating_value' => $value,
            ]);
        }

        session()->flash('message', 'Feedback submitted successfully. Thanks for helping us grow!');
        $this->reset(['transactionId', 'comments', 'ratingValues']);
    }


    public function render()
    {
        $ratingTypes = FeedbackRatingType::all();
        return view('livewire.guest.feedback.feedback-form', [
            'ratingTypes' => $ratingTypes
        ]);
    }
}
