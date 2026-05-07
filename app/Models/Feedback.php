<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\FeedbackRating;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $table = 'trn_feedback';

    protected $fillable = [
        'transaction_id',
        'transaction_number',
        'submitted_at',
        'comments',
        'status',
        'feedback_likes',
        'feedback_dislikes',
    ];

    // Add date casting
    protected $casts = [
        'submitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];




    // --------------------- Activity Logs ------------------ //
    protected static $logOnlyDirty = true; //Only changed attributes are logged

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly([
                'transaction_id',
                'transaction_number',
                'submitted_at',
                'comments',
                'is_approved'
            ])
            // 4.2 Automatically log only the attributes that have changed
            ->logOnlyDirty()
            // 4.3 Set a custom description for the feedback log event
            ->setDescriptionForEvent(fn(string $eventName) => "Feedback has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Feedback');
    }

    // --------------------- Relationships ------------------- //
    public function feedbackRatings()
    {
        return $this->hasMany(FeedbackRating::class, 'feedback_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    public function user()
    {
        return $this->hasOneThrough(
            TransactionUser::class,
            Transaction::class,
            'id', // Foreign key on transactions table
            'id', // Foreign key on transaction_users table
            'transaction_id', // Local key on feedback table
            'created_by' // Local key on transactions table
        );
    }
}
