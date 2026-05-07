<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DayTourRate extends Model
{
    use SoftDeletes, HasFactory, LogsActivity;

    protected $fillable = [
        'day_tour_id',
        'rate_name',
        'rate_type',
        'day_type',
        'adult_rate',
        'kid_rate',
        'min_guests',
        'max_guests',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'adult_rate' => 'decimal:2',
        'kid_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Activity Log Configuration
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'rate_name',
                'rate_type',
                'day_type',
                'adult_rate',
                'kid_rate',
                'min_guests',
                'max_guests',
                'is_active',
                'notes',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Day Tour Rate has been {$eventName}")
            ->useLogName('Day Tour Rate');
    }

    // Relationships
    public function dayTour()
    {
        return $this->belongsTo(DayTour::class);
    }

    // Add to DayTourRate model
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'day_tour_rate_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithRoom($query)
    {
        return $query->where('rate_type', 'with_room');
    }

    public function scopeWithoutRoom($query)
    {
        return $query->where('rate_type', 'without_room');
    }

    public function scopeByDayType($query, $dayType)
    {
        return $query->where('day_type', $dayType);
    }

    // Accessors
    public function getFormattedAdultRateAttribute()
    {
        return '₱' . number_format($this->adult_rate, 2);
    }

    public function getFormattedKidRateAttribute()
    {
        return '₱' . number_format($this->kid_rate, 2);
    }

    public function getRateTypeLabelAttribute()
    {
        return $this->rate_type === 'with_room' ? 'With Room' : 'Without Room';
    }

    public function getDayTypeLabelAttribute()
    {
        return ucfirst($this->day_type);
    }

    public function getGuestRangeAttribute()
    {
        if ($this->max_guests) {
            return "{$this->min_guests} - {$this->max_guests} guests";
        }
        return "{$this->min_guests}+ guests";
    }
}
