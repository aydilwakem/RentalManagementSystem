<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app')]
class EditMaintenance extends Component
{
    use WithFileUploads;

    public Maintenance $maintenance;
    public $name;
    public $description;
    public $reported_at;
    public $resolved_at;
    public $priority_status;
    public $maintenanceId;
    public $planned_datetime;
    public $property_id;
    public $properties;
    public $routine_datetime;
    public $resolved_images; 
    public $maintenance_images; 

    //For image uploads
    public $newImage;
    public $newImages = [];
    public $storedImages = [];
    public $displayImages = [];
    public $confirmDeleteImage = false;
    public $imageToDeleteId = null;


    //For resolved images uploads
    public $resolvedImages = [];
    public $resolvedImagePreviews = []; 
    public $resolvedPersistedPaths = []; 


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    //To display info of selected item
    public function mount(Maintenance $maintenance)
    {
        $this->name = $maintenance->name;
        $this->maintenanceId = $maintenance->id;
        $this->property_id = $maintenance->property_id;
        $this->description = $maintenance->description;
        $this->reported_at = optional($maintenance->reported_at)->format('Y-m-d');
        $this->resolved_at = optional($maintenance->resolved_at)->format('Y-m-d');
        $this->planned_datetime = $maintenance->planned_datetime
        ? Carbon::parse($maintenance->planned_datetime)->format('Y-m-d\TH:i')
        : null;
        $this->routine_datetime = $maintenance->routine_datetime
        ? Carbon::parse($maintenance->routine_datetime)->format('Y-m-d\TH:i')
        : null;
        $this->priority_status = $maintenance->priority_status;

        //To show rooms and event halls
        $this->properties = Property::whereIn('property_type_id', [1, 3])->get();
    
        //Initialize storedImages with unique IDs for sorting/removal
        $this->storedImages = collect($maintenance->maintenance_images ?? [])->map(function ($path) {
            return ['id' => Str::random(10), 'path' => $path]; // Assign a unique ID and store the path
        })->toArray();

        // Initial combined display images
        $this->updateDisplayImages();
    
    }

    public function updatedResolvedImages(){
        $this->validate([
        'resolvedImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
    ]);

        foreach ($this->resolvedImages as $image) {
            $this->resolvedImagePreviews[] = $image;
        }

        $this->resolvedImages = [];
    }

    public function removeResolvedImage($index)
    {
        if (isset($this->resolvedImagePreviews[$index])) {
            unset($this->resolvedImagePreviews[$index]);
            $this->resolvedImagePreviews = array_values($this->resolvedImagePreviews);
        } elseif (isset($this->resolvedPersistedPaths[$index])) {
            unset($this->resolvedPersistedPaths[$index]);
            $this->resolvedPersistedPaths = array_values($this->resolvedPersistedPaths);
        }
    }

    public function updateMaintenance()
    {
        try{
        // Validate form input
        $this->validate([
            'name' => "required|string|unique:mnt_maintenance,name,{$this->maintenanceId},id",
            'property_id' => 'required|exists:properties,id',
            'description' => 'required|string',
            'reported_at' => 'required|date',
            'resolved_at' => 'nullable|date|after_or_equal:reported_at',
            'planned_datetime' =>'nullable|date',
            'routine_datetime' =>'nullable|date',
            'priority_status' => 'required|in:emergency,urgent,routine,planned',
            'newImages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            

            'resolvedImages' => 'nullable|array',
            'resolvedImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

    // Handle image upload if a new one is selected
        $finalImagePaths = [];
        foreach ($this->displayImages as $imageItem) {
            if (isset($imageItem['path'])) {
                // uploaded image
                $finalImagePaths[] = $imageItem['path'];
            } elseif (isset($imageItem['object'])) {
                // new temp file so store
                $path = $imageItem['object']->store('maintenances', 'public');
                $finalImagePaths[] = $path;
            }
        }

    //For resolved images
        $resolvedStoredPaths = [];
        foreach ($this->resolvedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('resolved-maintenances', 'public');
                    $resolvedStoredPaths[] = $path;
                } else {
                    session()->flash('error', 'Resolved image upload failed. Please try again.');
                    return;
                }
            }
        }

        $resolvedStoredPaths = array_merge($resolvedStoredPaths, $this->resolvedPersistedPaths);

        // Update Maintenance
        $this->maintenance->update([
            'name' => $this->name,
            'property_id' => $this->property_id,
            'description' => $this->description,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'planned_datetime' => $this->planned_datetime,
            'priority_status' => $this->priority_status,
            'maintenance_images' => $finalImagePaths,
            'resolved_images' => $resolvedStoredPaths,
            'routine_datetime' => $this->routine_datetime,
        ]);

        // Check if 'resolved_at' is set and if so, add a specific session message
    if ($this->resolved_at) {
        session()->flash('message', 'Maintenance successfully resolved! Moved to Old Maintenances');
    } else {
        session()->flash('message', 'Maintenance item successfully updated!');
    }

        return redirect()->route('admin.maintenances');
    }

    // ------------------------ Image Methods ----------------------- //
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

    // ----------------- Removing Images Methods ----------------- //

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
                $this->storedImages = collect($this->storedImages)->filter(function($img) use ($imageToRemove) {
                    return $img['id'] !== $imageToRemove['id'];
                })->values()->toArray();
            }
            // if new upload, temp object lang,
            // remove from newImages if it's there
            elseif (isset($imageToRemove['object'])) {
                $this->newImages = collect($this->newImages)->filter(function($img) use ($imageToRemove) {
                    return $img->getFilename() !== $imageToRemove['id'];
                })->values()->toArray();
            }

            $this->updateDisplayImages(); // Re-update display array after removal
        }

        $this->confirmDeleteImage = false;
        $this->imageToDeleteId = null;

        session()->flash('message', 'Image successfully deleted.');
    }


    public function render()
    {
        return view('livewire.admin.maintenance.edit-maintenance');
    }
}
