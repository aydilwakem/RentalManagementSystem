<?php

namespace App\Livewire\Admin\DayTourRates;

use Livewire\Component;
use App\Models\DayTour;
use App\Models\DayTourRate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class EditDayTourRate extends Component
{
    public DayTourRate $dayTourRate;

    public $day_tour_id;
    public $rate_name;
    public $rate_type;
    public $day_type;
    public $adult_rate;
    public $kid_rate;
    public $min_guests;
    public $max_guests;
    public $is_active;
    public $notes;

    public $dayTours;
    public $confirmEditItem = false;

    public function mount(DayTourRate $dayTourRate)
    {
        $this->dayTourRate = $dayTourRate;
        $this->day_tour_id = $dayTourRate->day_tour_id;
        $this->rate_name = $dayTourRate->rate_name;
        $this->rate_type = $dayTourRate->rate_type;
        $this->day_type = $dayTourRate->day_type;
        $this->adult_rate = $dayTourRate->adult_rate;
        $this->kid_rate = $dayTourRate->kid_rate;
        $this->min_guests = $dayTourRate->min_guests;
        $this->max_guests = $dayTourRate->max_guests;
        $this->is_active = $dayTourRate->is_active;
        $this->notes = $dayTourRate->notes;

        $this->dayTours = DayTour::active()->get();
    }

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    public function updateDayTourRate()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmEditItem = false;
            throw $e;
        }

        $this->dayTourRate->update([
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

        session()->flash('message', 'Day Tour Rate successfully updated!');
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
                Rule::unique('day_tour_rates', 'rate_name')
                    ->ignore($this->dayTourRate->id)
                    ->where('day_tour_id', $this->day_tour_id)
                    ->whereNull('deleted_at')
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
        return view('livewire.admin.day-tour-rates.edit-day-tour-rate');
    }
}