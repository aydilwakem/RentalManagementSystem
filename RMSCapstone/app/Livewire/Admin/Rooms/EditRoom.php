<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditRoom extends Component
{
    use WithFileUploads;

    public Property $room;
    public $name_number;
    public $property_category_id;
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $occupancyRules = [];  // The list of occupancy rules
    public $turnover_duration;
    public $property_status;
    public $amount;
    public $image;
    public $newImage;
    public $features;            // All available features
    public $selectedFeatures = []; // Selected feature IDs
    public $occupancy_rules = [];
    public $roomCategories; // Store room categories for dropdown
    public $roomId;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    public function mount(Property $room)
    {
        $this->roomId = $room->id;
        $this->room = $room;
        $this->name_number = $room->name_number;
        $this->property_category_id = $room->property_category_id;
        $this->ideal_guest = $room->ideal_guest;
        $this->max_adults = $room->max_adults;
        $this->max_kids = $room->max_kids;
        $this->turnover_duration = $room->turnover_duration;
        $this->property_status = $room->property_status;
        $this->amount = $room->amount;
        $this->image = $room->image;
        $this->roomCategories = PropertyCategory::all();
        $this->occupancy_rules = $room->occupancy_rules ?? []; // If null, fallback to empty array


        $this->features = PropertyFeature::all();
        $this->selectedFeatures = $room->features()->pluck('property_features.id')->toArray();
    }

    public function addRule()
    {
        $this->occupancy_rules[] = ['adults' => 2, 'kids' => 2]; // Default rule
    }

    public function removeRule($index)
    {
        unset($this->occupancy_rules[$index]);
        $this->occupancy_rules = array_values($this->occupancy_rules); // Re-index array
    }

    public function updateRoom()
    {
        try {
            $this->validate([
                'name_number' => "required|string|max:255|unique:properties,name_number,{$this->roomId},id",
                'property_category_id' => 'nullable|exists:property_categories,id',
                'ideal_guest' => 'required|integer|min:1',
                'max_adults' => 'required|integer|min:1',
                'max_kids' => 'required|integer|min:0',
                'turnover_duration' => 'required|integer|min:1',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:1000000.00',
                'newImage' => 'nullable|image|max:2048',
                'occupancy_rules' => 'required|array',
                'occupancy_rules.*.adults' => 'required|integer|min:0',
                'occupancy_rules.*.kids' => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }

        // Handle image upload if a new one is selected
        if ($this->newImage) {
            if ($this->room->image) {
                Storage::disk('public')->delete($this->room->image);
            }
            $this->image = $this->newImage->store('rooms', 'public');
        }

        // Update room details
        $this->room->update([
            'name_number' => $this->name_number,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'amount' => $this->amount,
            'image' => $this->image,
            'occupancy_rules' => $this->occupancy_rules,
        ]);

        $this->room->features()->sync($this->selectedFeatures);

        session()->flash('message', 'Room successfully updated!');

        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.rooms.edit-room', [
            'roomCategories' => $this->roomCategories,
        ]);
    }
}
