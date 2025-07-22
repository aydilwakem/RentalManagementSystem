<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Create Amenity') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-2xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Amenity</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.amenities') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form Container -->
            <form wire:submit.prevent="" class="flex flex-col h-full space-y-6 min-h-[200px]">
                <!-- Name -->
                <div class="mt-4">
                    <label for="name" class="block mb-2 text-sm font-semibold text-gray-800 dark:text-gray-200">Amenity
                        Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" id="name" class="block w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 placeholder-gray-400 focus:ring-green-600 focus:border-green-600 focus:outline-none sm:text-base
                    dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:placeholder-gray-400"
                        placeholder="Ex. Free Wi-Fi, Toiletries, Kettle" required autocomplete="off">
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quanity -->
                <div>
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Quantity
                    </label>
                    <input type="number" wire:model="quantity" id="quantity" onwheel="this.blur()" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" min="1"
                        max="30" placeholder="Ex. 10">
                    @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Amenity Type -->
                <div>
                    <label for="property_feature_type"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amenity
                        Type <span class="text-red-500">*</span></label>
                    <select wire:model="property_feature_type" id="property_feature_type" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        <option value="">Select Amenity Type</option>
                        <option value="appliance">Appliance</option>
                        <option value="equipment">Equipment</option>
                        <option value="utility">Utility</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="service">Service</option>
                        <option value="fixture">Fixture</option>
                    </select>
                    @error('property_feature_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Amenity Status <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 dark:text-gray-200">Inactive</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" id="is_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                   ">
                            </div>
                            <div
                                class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                        <span class="text-gray-700 dark:text-gray-200">Active</span>
                    </div>
                    @error('is_active')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Space to push buttons to bottom -->
                <div class="flex-grow"></div>

                <!-- Action Buttons -->
                <div class="flex justify-between space-x-3 pt-4 mt-auto">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:loading.attr="disabled" wire:click="confirmCreate">
                        Create Amenity
                    </x-button>
                </div>
            </form>


            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Confirm Amenity Creation') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to create this amenity?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3" wire:click="saveAmenity" wire:loading.attr="disabled">
                        {{ __('Create Amenity') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>