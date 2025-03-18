<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event details') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit event</h2>


        <form wire:submit.prevent="updateEvent">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Event Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Event Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter event name" required>
                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Event Category -->
                <div>
                    <label for="event_category_id" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Category</label>
                    <select wire:model="event_category_id" id="event_category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Category</option>
                        @foreach ($eventCategories as $eventCategory)
                        <option value="{{ $eventCategory->id }}">{{ $eventCategory->name }}</option>
                        @endforeach
                    </select>
                    @error('event_category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Event Hall -->
                <div>
                    <label for="event_hall_id" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Hall</label>
                    <select wire:model="event_hall_id" id="event_hall_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Event Hall</option>
                        @foreach ($eventHalls as $eventHall)
                        <option value="{{ $eventHall->id }}">{{ $eventHall->name }}</option>
                        @endforeach
                    </select>
                    @error('event_hall_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Company Name --}}
                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company
                        Name</label>
                    <input type="text" wire:model="company_name" id="company_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter company name" required>
                    @error('company_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Contact Person --}}
                <div>
                    <label for="contact_person" class="block mb-2 text-sm font-medium text-gray-900">Contact
                        Person</label>
                    <input type="text" wire:model="contact_person" id="contact_person"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter contact person" required>
                    @error('contact_person')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="text" wire:model="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter contact person email" required>
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date Start --}}
                <div>
                    <label for="event_date_start" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Date Start</label>
                    <input type="date" wire:model="event_date_start" id="event_date_start"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('event_date_start')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date End --}}
                <div>
                    <label for="event_date_end" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Date End</label>
                    <input type="date" wire:model="event_date_end" id="event_date_end"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('event_date_end')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Event Time --}}
                <div>
                    <label for="event_time" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Time</label>
                    <input type="time" wire:model="event_time" id="event_time"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('event_time')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Capacity --}}
                <div>
                    <label for="capacity" class="block mb-2 text-sm font-medium text-gray-900">Capacity</label>
                    <input type="number" wire:model="capacity" id="capacity" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Event capacity" required></input>
                    @error('capacity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Total Amount --}}
                <div>
                    <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total
                        Amount</label>
                    <input type="number" wire:model="total_amount" id="total_amount" rows="8"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter total amount" required></input>
                    @error('total_amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Event Status -->
                <div class="sm:col-span-2">
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Status</label>
                    <select wire:model="status" id="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">--Select Status--</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="on-going">On-Going</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Event Request -->
                <div class="sm:col-span-2">
                    <label for="requests" class="block mb-2 text-sm font-medium text-gray-900">Event
                        Requests</label>
                    <textarea wire:model="requests" id="requests"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 resize-none"
                        placeholder="Enter event requests" required></textarea>
                    @error('requests')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" class="mt-6">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

</div>