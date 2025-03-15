<div class="min-h-[550px] container mx-auto p-10 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event Hall') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Event Hall</h2>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form wire:submit.prevent="updateEventHall">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Event Hall -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Event Name
                        Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type event category name" required>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="description" id="description" rows="8"
                        class="block p-2.5 max-h-20 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 resize-none"
                        placeholder="Your description here"></textarea>
                </div>

                <!-- Amount -->
                <div class="sm:col-span-2">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                    <input type="number" wire:model="amount" id="amount" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall amount"></input>
                </div>

                <!-- Capacity -->
                <div class="sm:col-span-2">
                    <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                    <input type="number" wire:model="capacity" id="capacity" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall capacity"></input>
                </div>

                <!-- Extra Charge Per Hour -->
                <div class="sm:col-span-2">
                    <label for="extra_charge_per_hr" class="block mb-2 text-sm font-medium text-gray-900">Extra
                        Charge Per Hour</label>
                    <input type="number" wire:model="extra_charge_per_hr" id="extra_charge_per_hr" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event hall extra charge"></input>
                </div>

                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                    <input accept="image/png, image/jpeg" type="file" wire:model="newImage" id="image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    <!-- Error Message -->
                    @error('newImage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Loading Indicator (Shows when file is being uploaded) -->
                    <div wire:loading wire:target="newImage" class="mt-2 text-blue-600">
                        Uploading image...
                    </div>

                    <!-- Image Preview (Shows New Image if Selected, Otherwise Shows Current Image) -->
                    <div class="mt-2">
                        @if ($newImage)
                            <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($image)
                            <img src="{{ asset('storage/' . $image) }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button" class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" class="mt-6"
                    wire:loading.attr="disabled" wire:target="newImage">
                    Save Event Hall
                </x-button>
            </div>
        </form>
    </div>

</div>
