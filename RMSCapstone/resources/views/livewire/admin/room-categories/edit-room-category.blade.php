<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Category</h2>
            <form wire:submit.prevent="updateCategory">
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
                        <input type="file" wire:model="image" id="image"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                        <!-- Existing Image Preview -->
                        @if ($image)
                            <img src="{{ asset('storage/' . $image) }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif


                        <!-- New Image Preview -->
                        @if ($image && method_exists($image, 'temporaryUrl'))
                            <div class="mt-2">
                                <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                    Update Category
                </button>
            </form>
        </div>
    </section>
</div>