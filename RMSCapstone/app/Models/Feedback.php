<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trn_feedback';

    protected $fillable = [
        'transaction_id',
        'submitted_at',
        'comments',
    ];

    public function feedbackRatings()
    {
        return $this->hasMany(FeedbackRating::class, 'feedback_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
