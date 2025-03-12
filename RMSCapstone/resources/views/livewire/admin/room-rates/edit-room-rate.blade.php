<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Room Rate</h2>
            <form wire:submit.prevent="updateRoomRate">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Room Rate Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Rate Name</label>
                        <input type="text" wire:model="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Room Name -->
                    <div class="sm:col-span-2">
                        <label for="room_id" class="block mb-2 text-sm font-medium text-gray-900">Room Name</label>
                        <select wire:model="room_id" id="room_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                        @error('room_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Start Date</label>
                        <input type="date" wire:model="start_date" id="start_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">End Date</label>
                        <input type="date" wire:model="end_date" id="end_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                        <input type="number" wire:model="amount" id="amount"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Extra Person Charge -->
                    <div>
                        <label for="extra_person_charge" class="block mb-2 text-sm font-medium text-gray-900">Extra Person Charge</label>
                        <input type="number" wire:model="extra_person_charge" id="extra_person_charge"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        @error('extra_person_charge') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Extended Stay Charge Per Hour -->
                    <div>
                        <label for="extended_stay_charge_per_hr" class="block mb-2 text-sm font-medium text-gray-900">Extra Stay Charge Per Hour</label>
                        <input type="number" wire:model="extended_stay_charge_per_hr" id="extended_stay_charge_per_hr"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        @error('extended_stay_charge_per_hr') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rate Type -->
                    <div class="sm:col-span-2">
                        <label for="rate_type" class="block mb-2 text-sm font-medium text-gray-900">Rate Type</label>
                        <select wire:model="rate_type" id="rate_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="Weekdays">Weekdays</option>
                            <option value="Weekend">Weekend</option>
                        </select>
                        @error('rate_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea wire:model="description" id="description"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            rows="5"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700"
                    wire:loading.attr="disabled">
                    Update Room Rate
                </button>
            </form>
        </div>
    </section>
</div>