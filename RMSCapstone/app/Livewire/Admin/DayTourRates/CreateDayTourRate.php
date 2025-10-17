<?php

namespace App\Livewire\Admin\DayTourRates;

use Livewire\Component;
use App\Models\DayTour;
use App\Models\DayTourRate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class CreateDayTourRate extends Component
{
    public $day_tour_id;
    public $rate_name;
    public $rate_type = 'without_room';
    public $day_type = 'weekday';
    public $adult_rate;
    public $kid_rate;
    public $min_guests = 1;
    public $max_guests;
    public $is_active = true;
    public $notes;

    public $dayTours;
    public $confirmCreateItem = false;

    public function mount()
    {
        $this->dayTours = DayTour::active()->get();
    }

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveDayTourRate()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        // Create the day tour rate
        DayTourRate::create([
            'day_tour_id' => $this->day_tour_id,
            'rate_name' => $this->rate_name,
            'rate_type' => $this->rate_type,
            'day_type' => $this->day_type,
            'adult_rate' => $this->adult_rate,
            'kid_rate' => $this->kid_rate,
            'min_guests' => $this->min_guests,
            'max_guests' => $this->max_guests,
            'is_active' => $this->is_active,
            'notes' => $this->notes,
        ]);

        $this->reset([
            'day_tour_id',
            'rate_name',
            'rate_type',
            'day_type',
            'adult_rate',
            'kid_rate',
            'min_guests',
            'max_guests',
            'is_active',
            'notes'
        ]);

        session()->flash('message', 'Day Tour Rate successfully created!');
        return redirect()->route('admin.day-tour-rates');
    }

    protected function rules()
    {
        return [
            'day_tour_id' => 'required|exists:day_tours,id',
            'rate_name' => [
                'required',
                'string',
                'max:255',
                // Rule::unique('day_tour_rates', 'rate_name')
                //     ->where('day_tour_id', $this->day_tour_id)
                //     ->whereNull('deleted_at')
            ],
            'rate_type' => 'required|in:with_room,without_room',
            'day_type' => 'required|in:weekday,weekend,holiday',
            'adult_rate' => 'required|numeric|min:0|max:100000',
            'kid_rate' => 'required|numeric|min:0|max:100000',
            'min_guests' => 'required|integer|min:1',
            'max_guests' => 'nullable|integer|min:1|gt:min_guests',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        return view('livewire.admin.day-tour-rates.create-day-tour-rate');
    }
}
