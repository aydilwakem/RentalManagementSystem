<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackRating extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trn_feedback_ratings';

    protected $fillable = [
        'feedback_id',
        'rating_type_id',
        'rating_value',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id', 'id');
    }

    public function ratingType()
    {
        return $this->belongsTo(FeedbackRatingType::class, 'rating_type_id', 'id');
    }
}
