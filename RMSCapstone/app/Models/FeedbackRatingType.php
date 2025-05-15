<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackRatingType extends Model
{
    protected $table = 'trn_feedback_rating_types';

    protected $fillable = [
        'rating_name',
    ];

    public function feedbackRatings()
    {
        return $this->hasMany(FeedbackRating::class, 'rating_type_id', 'id');
    }
}
