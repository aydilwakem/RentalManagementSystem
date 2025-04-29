<?php

namespace App\Livewire\Admin\Rooms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyFeature;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $name_number;
    public $property_category_id;
    public $property_type_id = 1; // Room
    public $ideal_guest;
    public $max_adults;
    public $max_kids;
    public $turnover_duration;
    public $property_status = 'available'; // Default
    public $amount;
    public $image;

    public $selectedFeatures = [];        // Selected feature IDs
    public $features = [];     // All features to show in UI
    public $roomCategories;       // All room categories
    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function mount()
    {
        $this->roomCategories = PropertyCategory::all();   // Load categories
        //mount only room inclusions
        $this->features = PropertyFeature::where('property_type_id', 1)->get();
    }

    public function saveRoom()
    {
        try {
            $this->validate([
                'name_number' => 'required|string|max:255|unique:properties,name_number',
                'property_category_id' => 'required|exists:property_categories,id',
                'property_type_id' => 'required|exists:property_types,id',
                'ideal_guest' => 'required|integer|min:1',
                'max_adults' => 'required|integer|min:1',
                'max_kids' => 'required|integer|min:0',
                'turnover_duration' => 'required|string',
                'property_status' => 'required|in:available,booked,out_of_service',
                'amount' => 'required|numeric|min:100|max:1000000.00',
                'image' => 'nullable|image|max:1024',
                'selectedFeatures' => 'nullable|array',
                'selectedFeatures.*' => 'exists:property_features,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        $imagePath = null;
        if ($this->image) {
            if (!$this->image->isValid()) {
                session()->flash('error', 'Image upload failed. Please try again.');
                return;
            }
            $imagePath = $this->image->store('rooms', 'public');
        }

        // Create the room
        $room = Property::create([
            'name_number' => $this->name_number,
            'property_type_id' => $this->property_type_id,
            'property_category_id' => $this->property_category_id,
            'ideal_guest' => $this->ideal_guest,
            'max_adults' => $this->max_adults,
            'max_kids' => $this->max_kids,
            'turnover_duration' => $this->turnover_duration,
            'property_status' => $this->property_status,
            'amount' => $this->amount,
            'image' => $imagePath,
        ]);

        // Attach selected features to pivot
        if (!empty($this->features)) {
            $room->features()->attach($this->features);
        }

        // Reset the form
        $this->reset([
            'name_number',
            'property_category_id',
            'ideal_guest',
            'max_adults',
            'max_kids',
            'turnover_duration',
            'property_status',
            'amount',
            'image',
            'selectedFeatures',
        ]);

        session()->flash('message', 'Room successfully created!');
        return redirect()->route('admin.rooms');
    }

    public function render()
    {
        return view('livewire.admin.rooms.create-room', [
            'roomCategories' => $this->roomCategories,
            'features' => $this->features,
        ]);
    }
}
