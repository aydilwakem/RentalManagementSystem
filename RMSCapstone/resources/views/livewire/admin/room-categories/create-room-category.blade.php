<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add a new Category</h2>

            {{-- Display Validation Errors --}}
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form wire:submit.prevent="saveCategory">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name of Category -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" wire:model="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Type category name" required>
                    </div>

                    <!-- Available Amenities (Dynamic) -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Amenities</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($amenities as $amenity)
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="selectedAmenities" value="{{ $amenity->id }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label class="ms-2 text-sm font-medium text-gray-900">
                                    {{ $amenity->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea wire:model="description" id="description" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Your description here"></textarea>
                    </div>

                    <!-- Image Upload -->
                    <div class="sm:col-span-2">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                        <input accept="image/png, image/jpeg" type="file" wire:model="image" id="image"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                        <!-- Error Message -->
                        @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Loading Indicator (Shows when file is being uploaded) -->
                        <div wire:loading wire:target="image" class="mt-2 text-blue-600">
                            Uploading image...
                        </div>

                        <!-- Image Preview (Only if an image is selected and processed) -->
                        @if ($image && method_exists($image, 'temporaryUrl'))
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        </div>
                        @endif
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700"
                    wire:loading.attr="disabled" wire:target="image">
                    Add Category
                </button>

            </form>
        </div>
    </section>
</div>