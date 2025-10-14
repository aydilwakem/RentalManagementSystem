<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DayTour;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class CreateDayTour extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $inclusions;
    public $exclusions;
    public $terms_conditions;
    public $duration_hours = 8;
    public $start_time = '08:00';
    public $end_time = '16:00';
    public $max_guests = 50;
    public $base_price = 0;
    public $is_active = true;

    public $main_image;
    public $newImages = [];
    public $uploadedImagePreviews = [];
    public $persistedImagePaths = [];

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
        ]);

        foreach ($this->newImages as $image) {
            $this->uploadedImagePreviews[] = $image;
        }
        $this->newImages = [];
    }

    public function removeImage($index)
    {
        if (isset($this->uploadedImagePreviews[$index])) {
            unset($this->uploadedImagePreviews[$index]);
            $this->uploadedImagePreviews = array_values($this->uploadedImagePreviews);
        } elseif (isset($this->persistedImagePaths[$index])) {
            unset($this->persistedImagePaths[$index]);
            $this->persistedImagePaths = array_values($this->persistedImagePaths);
        }
    }

    public function saveDayTour()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        $allStoredImagePaths = [];

        // Store newly uploaded images
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('daytours', 'public');
                    $allStoredImagePaths[] = $path;
                }
            }
        }

        $allStoredImagePaths = array_merge($allStoredImagePaths, $this->persistedImagePaths);

        // Store main image
        $mainImagePath = null;
        if ($this->main_image && $this->main_image->isValid()) {
            $mainImagePath = $this->main_image->store('daytours', 'public');
        }

        // Create the day tour
        $dayTour = DayTour::create([
            'name' => $this->name,
            'description' => $this->description,
            'inclusions' => $this->inclusions,
            'exclusions' => $this->exclusions,
            'terms_conditions' => $this->terms_conditions,
            'duration_hours' => $this->duration_hours,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'max_guests' => $this->max_guests,
            'base_price' => $this->base_price,
            'is_active' => $this->is_active,
            'main_image' => $mainImagePath,
            'images' => $allStoredImagePaths,
        ]);

        $this->reset([
            'name', 'description', 'inclusions', 'exclusions', 'terms_conditions',
            'duration_hours', 'start_time', 'end_time', 'max_guests', 'base_price',
            'is_active', 'main_image', 'newImages', 'uploadedImagePreviews', 'persistedImagePaths'
        ]);

        session()->flash('message', 'Day Tour successfully created!');
        return redirect()->route('admin.day-tours');
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('day_tours', 'name')->whereNull('deleted_at'),
            ],
            'description' => 'required|string|min:10|max:1000',
            'inclusions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'terms_conditions' => 'nullable|string|max:2000',
            'duration_hours' => 'required|integer|min:1|max:24',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_guests' => 'required|integer|min:1|max:1000',
            'base_price' => 'required|numeric|min:0|max:100000',
            'is_active' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2024',
            'newImages' => 'nullable|array',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
        ];
    }

    public function render()
    {
        return view('livewire.admin.day-tours.create-day-tour');
    }
}