<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add a new Category</h2>
            <form action="#">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Name of Category -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Type category name" required>
                    </div>

                    <!-- Available Amenities -->
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Amenities</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <input id="wifi" type="checkbox" value="WiFi"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="wifi" class="ms-2 text-sm font-medium text-gray-900">WiFi</label>
                            </div>
                            <div class="flex items-center">
                                <input id="parking" type="checkbox" value="Parking"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="parking" class="ms-2 text-sm font-medium text-gray-900">Parking</label>
                            </div>
                            <div class="flex items-center">
                                <input id="pool" type="checkbox" value="Swimming Pool"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="pool" class="ms-2 text-sm font-medium text-gray-900">Swimming Pool</label>
                            </div>
                            <div class="flex items-center">
                                <input id="gym" type="checkbox" value="Gym"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="gym" class="ms-2 text-sm font-medium text-gray-900">Gym</label>
                            </div>
                            <div class="flex items-center">
                                <input id="tv" type="checkbox" value="TV"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="tv" class="ms-2 text-sm font-medium text-gray-900">TV</label>
                            </div>
                            <div class="flex items-center">
                                <input id="ac" type="checkbox" value="Air Conditioning"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-blue-500">
                                <label for="ac" class="ms-2 text-sm font-medium text-gray-900">Air Conditioning</label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea id="description" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Your description here"></textarea>
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                    Add Category
                </button>
            </form>
        </div>
    </section>
</div>