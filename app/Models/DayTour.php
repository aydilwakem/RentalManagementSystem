<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DayTour extends Model
{
    use SoftDeletes, HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'inclusions',
        'exclusions',
        'terms_conditions',
        'duration_hours',
        'start_time',
        'end_time',
        'max_guests',
        'base_price',
        'is_active',
        'images',
        'main_image',
        'package_type',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Activity Log Configuration
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
                'inclusions',
                'exclusions',
                'terms_conditions',
                'duration_hours',
                'start_time',
                'end_time',
                'max_guests',
                'base_price',
                'is_active',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Day Tour has been {$eventName}")
            ->useLogName('Day Tour');
    }

    // Relationships
    public function rates()
    {
        return $this->hasMany(DayTourRate::class);
    }

    public function activeRates()
    {
        return $this->hasMany(DayTourRate::class)->where('is_active', true);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'day_tour_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    // Accessors
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/rms-default.png');
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [asset('images/rms-default.png')];
        }

        return collect($this->images)->map(function ($image) {
            return asset('storage/' . $image);
        })->toArray();
    }

    public function getFormattedInclusionsAttribute()
    {
        if (!$this->inclusions) return [];
        return explode("\n", $this->inclusions);
    }

    public function getFormattedExclusionsAttribute()
    {
        if (!$this->exclusions) return [];
        return explode("\n", $this->exclusions);
    }
}
