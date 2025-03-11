<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add New Room Rate</h2>
            <form wire:submit.prevent="saveRoomRate">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Room Rta Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                        <input type="text" wire:model="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter room name">
                    </div>

                    <!-- Room Name-->
                    <div class="sm:col-span-2">
                        <label for="room_id" class="block mb-2 text-sm font-medium text-gray-900">Room
                            Name</label>
                        <select wire:model="room_id" id="room_d"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ideal Guest -->
                    <div>
                        <label for="ideal_guest" class="block mb-2 text-sm font-medium text-gray-900">Ideal
                            Guest</label>
                        <input type="number" wire:model="ideal_guest" id="ideal_guest"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Max Adults -->
                    <div>
                        <label for="max_adults" class="block mb-2 text-sm font-medium text-gray-900">Max Adults</label>
                        <input type="number" wire:model="max_adults" id="max_adults" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Max Kids -->
                    <div>
                        <label for="max_kids" class="block mb-2 text-sm font-medium text-gray-900">Max Kids</label>
                        <input type="number" wire:model="max_kids" id="max_kids" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Turnover Duration -->
                    <div>
                        <label for="turnover_duration" class="block mb-2 text-sm font-medium text-gray-900">Turnover
                            Duration (Hours)</label>
                        <input type="number" wire:model="turnover_duration" id="turnover_duration" min="1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Room Status -->
                    <div class="sm:col-span-2">
                        <label for="room_status" class="block mb-2 text-sm font-medium text-gray-900">Room
                            Status</label>
                        <select wire:model="room_status" id="room_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="Available">Available</option>
                            <option value="Booked">Booked</option>
                            <option value="Out of Service">Out of Service</option>
                        </select>
                    </div>


                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700"
                    wire:loading.attr="disabled" wire:target="image">
                    Add Room Rate
                </button>
            </form>
        </div>
    </section>
</div>