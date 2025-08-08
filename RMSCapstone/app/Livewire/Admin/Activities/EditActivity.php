<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditActivity extends Component
{
    use WithFileUploads;

    //Public declaration of activity variable with model
    public Activity $activity;

    //Public declarations of fillable fields
    public $name;
    public $description;
    public $amount;
    public $inclusions;
    public $image;
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;
    public $activityId;

    //Public declaration of edit confirmation modal
    public $confirmEditItem = false;



    public $schedule_type;
    public $available_times;


    public function addTime()
    {
        $this->available_times[] = '';
    }

    public function removeTime($index)
    {
        unset($this->available_times[$index]);
        $this->available_times = array_values($this->available_times); // Reindex
    }



    //Method to make the modal true
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function updatedNewImages()
    {
        $this->updateDisplayImages();
    }

    protected function updateDisplayImages()
    {
        // Extract current new (temporary) images from displayImages to keep them
        $existingTempImages = collect($this->displayImages)->filter(function ($image) {
            return !in_array($image, $this->storedImages);
        });

        // Map new uploaded images with unique IDs
        $newImagePreviewsWithIds = collect($this->newImages)->map(function ($image) {
            return ['id' => $image->getFilename(), 'object' => $image];
        });

        // Merge stored images, existing temp images, and new uploads
        $this->displayImages = array_merge($this->storedImages, $existingTempImages->toArray(), $newImagePreviewsWithIds->toArray());

        // Clear newImages to reset input
        $this->newImages = [];
    }

    public function confirmImageDelete($id)
    {
        $this->imageToDeleteId = $id;
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
    {
        // find image by id
        $indexToRemove = null;
        foreach ($this->displayImages as $key => $image) {
            if ($image['id'] === $this->imageToDeleteId) {
                $indexToRemove = $key;
                break;
            }
        }

        if (is_numeric($indexToRemove)) {
            $imageToRemove = $this->displayImages[$indexToRemove];

            // if stored image, delete from storage
            if (isset($imageToRemove['path'])) {
                Storage::disk('public')->delete($imageToRemove['path']);
                // and remove from the storedImages array
                $this->storedImages = collect($this->storedImages)->filter(function ($img) use ($imageToRemove) {
                    return $img['id'] !== $imageToRemove['id'];
                })->values()->toArray();
            }
            // if new upload, temp object lang,
            // remove from newImages if it's there
            elseif (isset($imageToRemove['object'])) {
                $this->newImages = collect($this->newImages)->filter(function ($img) use ($imageToRemove) {
                    return $img->getFilename() !== $imageToRemove['id'];
                })->values()->toArray();
            }

            $this->updateDisplayImages(); // Re-update display array after removal
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;

        session()->flash('message', 'Image successfully deleted.');
    }

    // To display info of the selected activity
    public function mount(Activity $activity)
    {
        $this->activity = $activity;
        $this->activityId = $activity->id;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->amount = $activity->amount;
        $this->inclusions = $activity->inclusions;
        $this->schedule_type = $activity->schedule_type;
        $this->available_times = $activity->available_times ?? [];


        // initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($activity->images ?? [])
            ->map(function ($path) {
                return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
            })
            ->toArray();

        // Initial combined display images
        $this->updateDisplayImages();
    }

    /**
     * Validate and update an existing activity record.
     * Handles optional image replacement, updates fields, and flashes a success message.
     */
    public function updateActivity()
    {
        //Validates all required fields
        try {
            $this->validate([
                'name' => "required|string|max:255|unique:prd_activities,name,{$this->activityId},id",
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0|max:10000',
                'inclusions' => 'nullable|string',
                'newImage' => 'nullable|image|max:2048',
                'newImages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'schedule_type' => 'required|in:no_schedule,system,guest',
                'available_times' => 'nullable|array',
                'available_times.*' => 'nullable|date_format:H:i',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        } //Handles constraints

        // Handle image upload if a new one is selected
        $finalImagePaths = [];
        foreach ($this->displayImages as $imageItem) {
            if (isset($imageItem['path'])) {
                // uploaded image
                $finalImagePaths[] = $imageItem['path'];
            } elseif (isset($imageItem['object'])) {
                // new temp file so store
                $path = $imageItem['object']->store('activities', 'public');
                $finalImagePaths[] = $path;
            }
        }

        // Update Activity
        $this->activity->update([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'inclusions' => $this->inclusions,
            'images' => $finalImagePaths,
            'schedule_type' => $this->schedule_type,
            'available_times' => $this->schedule_type === 'system' ? array_filter($this->available_times) : null,
        ]);

        session()->flash('message', 'Activity successfully updated!');

        return redirect()->route('admin.activities');
    }

    public function render()
    {
        return view('livewire.admin.activities.edit-activity');
    }
}
