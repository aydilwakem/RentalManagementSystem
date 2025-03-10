<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add a New Event</h2>
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form wire:submit.prevent="saveEvent">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Event Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Event Name</label>
                        <input type="text" wire:model="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter event name">
                    </div>

                    <!-- Event Category -->
                    <div>
                        <label for="event_category_id" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Category</label>
                        <select wire:model="event_category_id" id="event_category_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Category</option>
                            @foreach($eventCategories as $eventCategory)
                            <option value="{{ $eventCategory->id }}">{{ $eventCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Event Hall -->
                    <div>
                        <label for="event_hall_id" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Hall</label>
                        <select wire:model="event_hall_id" id="event_hall_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Event Hall</option>
                            @foreach($eventHalls as $eventHall)
                            <option value="{{ $eventHall->id }}">{{ $eventHall->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Company Name --}}
                    <div>
                        <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company
                            Name</label>
                        <input type="text" wire:model="company_name" id="company_name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter company name">
                    </div>

                    {{-- Contact Person --}}
                    <div>
                        <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-900">Contact
                            Person</label>
                        <input type="text" wire:model="contact_person" id="contact_person" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter contact person">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="text" wire:model="email" id="email" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter contact person email">
                    </div>

                    {{-- Date Start --}}
                    <div>
                        <label for="event_date_start" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Date Start</label>
                        <input type="date" wire:model="event_date_start" id="event_date_start"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    {{-- Date End --}}
                    <div>
                        <label for="event_date_end" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Date End</label>
                        <input type="date" wire:model="event_date_end" id="event_date_end"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    {{-- Event Time --}}
                    <div>
                        <label for="event_time" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Time</label>
                        <input type="time" wire:model="event_time" id="event_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    {{-- Capacity --}}
                    <div>
                        <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                        <input type="number" wire:model="capacity" id="capacity" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Event capacity"></input>
                    </div>

                    {{-- Total Amount --}}
                    <div>
                        <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total
                            Amount</label>
                        <input type="number" wire:model="total_amount" id="total_amount" rows="8"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter total amount"></input>
                    </div>

                    <!-- Event Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Status</label>
                        <select wire:model="status" id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="confirmed">Confirmed</option>
                            <option value="on-going">On-Going</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="requests" class="block mb-2 text-sm font-medium text-gray-900">Event
                            Requests</label>
                        <textarea wire:model="requests" id="requests" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter event requests"></textarea>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                        Add Event
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>