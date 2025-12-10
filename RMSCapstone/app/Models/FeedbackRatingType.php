<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FeedbackRatingType extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'trn_feedback_rating_types';

    protected $fillable = [
        'rating_name',
    ];

    // ---------------- Activity Logs ------------------ //
    protected static $logOnlyDirty = true; //Only changed attributes are logged 

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            // 4.1 Specify which attributes to log
            ->logOnly(['rating_name'])
            // 4.2 Automatically log only the attributes that have changed  
            ->logOnlyDirty()
            // 4.3 Set a custom description for the activity log event
            ->setDescriptionForEvent(fn(string $eventName) => "Feedback Rating Type has been {$eventName}")
            // 4.4 Optionally, you can set a custom log name for Property Model
            ->useLogName('Feedback Rating Type');
    }


    // ----------------- Relationships ------------------ //
    public function feedbackRatings()
    {
        return $this->hasMany(FeedbackRating::class, 'rating_type_id', 'id');
    }
}
