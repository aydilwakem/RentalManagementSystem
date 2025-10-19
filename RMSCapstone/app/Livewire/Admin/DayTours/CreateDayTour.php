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
    public $package_type = 'without_room';
    public $inclusions;
    public $exclusions;
    public $terms_conditions;
    public $duration_hours = 8;
    public $start_time = '08:00';
    public $end_time = '16:00';
    public $max_guests = 50;
    public $base_price;
    public $is_active = true;

    public $newImages = [];
    public $uploadedImagePreviews = [];
    public $persistedImagePaths = [];
    protected $listeners = ['reorderImages'];

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
            $this->uploadedImagePreviews[] = $image; // store temporary for preview
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

    public function reorderImages($order)
    {
        // Merge both arrays
        $allImages = array_merge($this->uploadedImagePreviews, $this->persistedImagePaths);

        $reordered = collect($order)->map(function ($i) use ($allImages) {
            return $allImages[$i];
        })->toArray();

        // Reset arrays
        $this->uploadedImagePreviews = [];
        $this->persistedImagePaths = [];

        foreach ($reordered as $img) {
            if (is_object($img) && method_exists($img, 'temporaryUrl')) {
                $this->uploadedImagePreviews[] = $img;
            } else {
                $this->persistedImagePaths[] = $img;
            }
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

        // Save uploaded previews
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && $imageObject->isValid()) {
                $path = $imageObject->store('daytours', 'public');
                $allStoredImagePaths[] = $path;
            }
        }

        // Merge persisted ones
        $allStoredImagePaths = array_merge($allStoredImagePaths, $this->persistedImagePaths);

        // First image = main image
        $mainImagePath = $allStoredImagePaths[0] ?? null;

        // Create the day tour
        $dayTour = DayTour::create([
            'name' => $this->name,
            'description' => $this->description,
            'package_type' => $this->package_type,
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
            'name', 'description', 'package_type', 'inclusions', 'exclusions', 'terms_conditions',
            'duration_hours', 'start_time', 'end_time', 'max_guests', 'base_price',
            'is_active', 'newImages', 'uploadedImagePreviews', 'persistedImagePaths'
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
            'package_type' => 'required|in:with_room,without_room',
            'description' => 'nullable|string|min:10|max:1000',
            'inclusions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'terms_conditions' => 'nullable|string|max:2000',
            'duration_hours' => 'required|integer|min:1|max:24',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_guests' => 'required|integer|min:1|max:1000',
            'base_price' => 'required|numeric|min:0|max:100000',
            'is_active' => 'boolean',
            'newImages' => 'nullable|array',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
        ];
    }

    public function render()
    {
        return view('livewire.admin.day-tours.create-day-tour');
    }
}
