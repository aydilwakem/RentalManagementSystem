<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg shadow-md">

    <h2 class="mb-6 text-2xl font-bold text-gray-900 text-center">Add New Reservation</h2>

    <div class="space-y-6">

        <!-- Guest Information Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" id="first_name" wire:model="first_name"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

            <!-- Middle Name -->
            <div>
                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input type="text" id="middle_name" wire:model="middle_name"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" id="last_name" wire:model="last_name"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

            <!-- Suffix -->
            <div>
                <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                <input type="text" id="suffix" wire:model="suffix"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" wire:model="email"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="text" id="contact_number" wire:model="contact_number"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <input type="text" id="company_name" wire:model="company_name"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                <input type="text" id="country" wire:model="country"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

        </div>

        <!-- Check-in Date and Check-out Date -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Check-in Date -->
            <div>
                <label for="check_in_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                <input type="date" id="check_in_date" wire:model.live="check_in_date"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

            <!-- Check-out Date -->
            <div>
                <label for="check_out_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                <input type="date" id="check_out_date" wire:model.live="check_out_date"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:border-green-500">
            </div>

        </div>

        <!-- Room and Activity Checkboxes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Room Checkboxes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Rooms</label>
                <div class="space-y-2">
                    @foreach($rooms as $room)
                    <div class="flex items-center bg-white p-4 rounded-lg shadow-md border border-gray-200">
                        <input type="checkbox" wire:model="selectedRooms" value="{{ $room->id }}"
                            id="room_{{ $room->id }}"
                            class="h-5 w-5 text-green-500 border-gray-300 rounded focus:ring-green-500">
                        <label for="room_{{ $room->id }}" class="ml-2 text-sm text-gray-700">{{ $room->name_number
                            }}</label>
                        <div class="ml-4 text-sm text-gray-600">
                            <p>Total Guests: {{ $room->total_guests }}</p>
                            <p>Max Kids: {{ $room->max_kids }}</p>
                            <p>Max Adults: {{ $room->max_adults }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Activity Checkboxes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Activities</label>
                <div class="space-y-2">
                    @foreach($activities as $activity)
                    <div class="flex items-center bg-white p-4 rounded-lg shadow-md border border-gray-200">
                        <input type="checkbox" wire:model="selectedActivities" value="{{ $activity->id }}"
                            id="activity_{{ $activity->id }}"
                            class="h-5 w-5 text-green-500 border-gray-300 rounded focus:ring-green-500">
                        <label for="activity_{{ $activity->id }}" class="ml-2 text-sm text-gray-700">{{ $activity->name
                            }}</label>
                        <div class="ml-4 text-sm text-gray-600">
                            <p>Extra Charge per Hour: ${{ $activity->extra_charge_per_hour }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>



    </div>
</div>