<?php

namespace App\Livewire\Admin\DayTours;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DayTour;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EditDayTour extends Component
{
    use WithFileUploads;

    public DayTour $dayTour;

    public $name;
    public $description;
    public $inclusions;
    public $exclusions;
    public $terms_conditions;
    public $duration_hours;
    public $start_time;
    public $end_time;
    public $max_guests;
    public $base_price;
    public $is_active;

    public $main_image;
    public $newMainImage;

    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];

    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;
    public $confirmEditItem = false;

    protected $listeners = ['updateImageOrder'];

    public function mount(DayTour $dayTour)
    {
        $this->dayTour = $dayTour;
        $this->name = $dayTour->name;
        $this->description = $dayTour->description;
        $this->inclusions = $dayTour->inclusions;
        $this->exclusions = $dayTour->exclusions;
        $this->terms_conditions = $dayTour->terms_conditions;
        $this->duration_hours = $dayTour->duration_hours;
        $this->start_time = $dayTour->start_time->format('H:i');
        $this->end_time = $dayTour->end_time->format('H:i');
        $this->max_guests = $dayTour->max_guests;
        $this->base_price = $dayTour->base_price;
        $this->is_active = $dayTour->is_active;
        $this->main_image = $dayTour->main_image;

        // Initialize stored images with IDs for reordering/removal
        $this->storedImages = collect($dayTour->images ?? [])
            ->map(function ($path) {
                return ['id' => Str::random(10), 'path' => $path];
            })
            ->toArray();

        $this->updateDisplayImages();
    }

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    public function updatedNewImages()
    {
        $this->updateDisplayImages();
    }

    protected function updateDisplayImages()
    {
        // Keep current temp images
        $existingTempImages = collect($this->displayImages)->filter(function ($image) {
            return !in_array($image, $this->storedImages);
        });

        // Wrap new uploads
        $newImagePreviewsWithIds = collect($this->newImages)->map(function ($image) {
            return ['id' => $image->getFilename(), 'object' => $image];
        });

        $this->displayImages = array_merge(
            $this->storedImages,
            $existingTempImages->toArray(),
            $newImagePreviewsWithIds->toArray()
        );

        $this->newImages = []; // reset input
    }

    public function confirmImageDelete($id)
    {
        $this->imageToDeleteId = $id;
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        $indexToRemove = null;
        foreach ($this->displayImages as $key => $image) {
            if ($image['id'] === $this->imageToDeleteId) {
                $indexToRemove = $key;
                break;
            }
        }

        if (is_numeric($indexToRemove)) {
            $imageToRemove = $this->displayImages[$indexToRemove];

            // Stored image
            if (isset($imageToRemove['path'])) {
                Storage::disk('public')->delete($imageToRemove['path']);
                $this->storedImages = collect($this->storedImages)
                    ->reject(fn($img) => $img['id'] === $imageToRemove['id'])
                    ->values()
                    ->toArray();
            }
            // Temp uploaded image
            elseif (isset($imageToRemove['object'])) {
                $this->newImages = collect($this->newImages)
                    ->reject(fn($img) => $img->getFilename() === $imageToRemove['id'])
                    ->values()
                    ->toArray();
            }

            $this->displayImages = collect($this->displayImages)
                ->reject(fn($img) => $img['id'] === $this->imageToDeleteId)
                ->values()
                ->toArray();

            $this->updateDisplayImages();
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;
    }

    public function updateDayTour()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->confirmEditItem = false;
            throw $e;
        }

        // Process all images
        $finalImagePaths = [];
        foreach ($this->displayImages as $imageItem) {
            if (isset($imageItem['path'])) {
                $finalImagePaths[] = $imageItem['path'];
            } elseif (isset($imageItem['object'])) {
                $path = $imageItem['object']->store('daytours', 'public');
                $finalImagePaths[] = $path;
            }
        }

        // Handle main image replacement
        $mainImagePath = $this->dayTour->main_image;
        if ($this->newMainImage && $this->newMainImage->isValid()) {
            if ($mainImagePath) {
                Storage::disk('public')->delete($mainImagePath);
            }
            $mainImagePath = $this->newMainImage->store('daytours', 'public');
        }

        $this->dayTour->update([
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
            'images' => $finalImagePaths,
        ]);

        session()->flash('message', 'Day Tour successfully updated!');
        return redirect()->route('admin.day-tours');
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('day_tours', 'name')->ignore($this->dayTour->id)->whereNull('deleted_at'),
            ],
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
            'newMainImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2024',
            'newImages' => 'nullable|array',
            'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
        ];
    }

    public function render()
    {
        return view('livewire.admin.day-tours.edit-day-tour');
    }
}
