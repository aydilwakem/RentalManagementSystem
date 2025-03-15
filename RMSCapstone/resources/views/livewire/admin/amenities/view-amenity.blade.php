<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Amenity') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center p-8">
           Amenity: {{ $amenity->name }}
        </h2>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate  href="{{ route('admin.edit-amenity', ['amenity' => $amenity->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="deleteAmenity({{ $amenity->id }})">
                Delete
            </x-button>

        </div>

    </div>
</div>
