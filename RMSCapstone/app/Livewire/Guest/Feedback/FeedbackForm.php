<?php

namespace App\Livewire\Guest\Feedback;

use Livewire\Component;
use App\Models\Feedback;
use App\Models\FeedbackRating;
use App\Models\FeedbackRatingType;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FeedbackForm extends Component
{

    public $transaction_number;
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
            'transaction_number' => 'required|string',
            'comments' => 'nullable|string',
            'ratingValues' => 'required|array',
        ];

        foreach ($expectedRatingTypeIds as $id) {
            $rules["ratingValues.$id"] = 'required|numeric|min:1|max:5';
        }

        $this->validate($rules);

        // Find in Transactions table with the transaction number

        $transaction = Transaction::where('transaction_number', $this->transaction_number)->first();

        // Create feedback
        $feedback = Feedback::create([
            'transaction_id' => $transaction ? $transaction->id : null,
            'transaction_number' => $this->transaction_number,
            'submitted_at' => now(),
            'comments' => $this->comments ?: '',
        ]);

        foreach ($this->ratingValues as $typeId => $value) {
            FeedbackRating::create([
                'feedback_id' => $feedback->id,
                'rating_type_id' => $typeId,
                'rating_value' => $value,
            ]);
        }


        session()->flash('message', 'Feedback submitted successfully. Thanks for helping us grow!');
        $this->reset(['transaction_number', 'comments', 'ratingValues']);
    }


    public function render()
    {
        $ratingTypes = FeedbackRatingType::all();
        return view('livewire.guest.feedback.feedback-form', [
            'ratingTypes' => $ratingTypes
        ]);
    }
}
