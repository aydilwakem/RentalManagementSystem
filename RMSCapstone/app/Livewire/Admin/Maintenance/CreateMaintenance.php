<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use App\Models\Property;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateMaintenance extends Component
{
    use WithFileUploads;
    
    public $name;
    public $description;
    public $reported_at;
    public $resolved_at;
    public $priority_status = '';
    public $planned_datetime;
    public $property_id;
    public $properties;
    public $routine_datetime; 

    //IMAGES
    public $maintenance_images; 
    public $newImages = [];
    public $uploadedImagePreviews = []; // temporary url for preview
    public $persistedImagePaths = []; // string paths once uploaded
    protected $listeners = ['updateImageOrder'];

    //For resolved images
    public $resolvedImages = [];
    public $resolvedImagePreviews = []; 
    public $resolvedPersistedPaths = []; 



    public $confirmCreateItem = false;

    //To show all properties for assignment
    public function mount()
    {
        //Attach rooms and event halls
       $this->properties = Property::whereIn('property_type_id', [1, 3])->get();

        // Set default reported_at to today
        $now = Carbon::now('Asia/Manila');
        $this->reported_at = $now->format('Y-m-d');
    }

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    // -------------------- New Image Method --------------------- //
    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
        ]);

        foreach ($this->newImages as $image) {
            $this->uploadedImagePreviews[] = $image; // store temporary for prview
        }
        $this->newImages = [];
    }


    //------------------------- Update the Resolved Images ---------------------- //
    public function updatedResolvedImages(){
        $this->validate([
        'resolvedImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
    ]);

        foreach ($this->resolvedImages as $image) {
            $this->resolvedImagePreviews[] = $image;
        }

        $this->resolvedImages = [];
    }

    //--------------------- Method to remove images ---------------------  //
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



    public function saveMaintenance()
    {
        try {
            // Validate form input
            $this->validate([
                'name' => 'required|string|unique:mnt_maintenance,name|regex:/^[A-Za-z\s\-]+$/',
                'description' => 'required|string|regex:/^[A-Za-z\s\-]+$/',
                'property_id' => 'required|exists:properties,id',
                'reported_at' => 'required|date',
                'resolved_at' => 'nullable|date|after_or_equal:reported_at',
                'planned_datetime' => 'nullable|date',
                'priority_status' => 'required|in:emergency,urgent,routine,planned',
                'routine_datetime' => 'nullable|date',

                //For images
                'newImages' => 'nullable|array',
                'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
            
                //For resolved images
                //Validate the resolved images
                'resolvedImages' => 'nullable|array',
                'resolvedImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
            
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        //For images
        $allStoredImagePaths = [];

        // store newly uploaded images
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('maintenances', 'public');
                    $allStoredImagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        $allStoredImagePaths = array_merge($allStoredImagePaths, $this->persistedImagePaths);

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


        //For datetime validation
        if ($this->priority_status === 'planned') {
            // Reformat planned_datetime before saving
            $this->planned_datetime = Carbon::parse($this->planned_datetime)->format('Y-m-d H:i:s');
        } else {
            $this->planned_datetime = null;
        }

        // Create Maintenance
        $maintenance = Maintenance::create([
            'name' => $this->name,
            'description' => $this->description,
            'property_id' => $this->property_id,
            'reported_at' => $this->reported_at,
            'resolved_at' => $this->resolved_at,
            'planned_datetime' => $this->planned_datetime,
            'priority_status' => $this->priority_status,
            'routine_datetime' => $this->routine_datetime,
            'maintenance_images' => $allStoredImagePaths,
            'resolved_images' => $resolvedStoredPaths,

        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'property_id', 'reported_at', 'resolved_at', 'priority_status', 'routine_datetime', 'maintenance_images']);

        // Flash message for success
        session()->flash('message', 'Maintenance successfully created!');

        // Redirect back to event categories list
        return redirect()->route('admin.maintenances');
    }

    public function render()
    {
        return view('livewire.admin.maintenance.create-maintenance');
    }
}
