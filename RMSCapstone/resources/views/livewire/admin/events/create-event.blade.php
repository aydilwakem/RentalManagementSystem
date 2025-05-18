<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
    <h2 class="mb-2 text-xl font-bold text-gray-900 text-center">Add New Event</h2>

    <form wire:submit.prevent="">
        <div class="grid gap-4 md:grid-cols-2 sm:gap-6">

            {{-- GUEST DETAILS --}}
            <div class="col-span-full md:col-span-2">
                <h3 class="block mb-4 text-lg font-semibold text-gray-900 dark:text-white">Guest Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                        <input type="text" wire:model="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('first_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle Name</label>
                        <input type="text" wire:model="middle_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('middle_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                        <input type="text" wire:model="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="text" wire:model="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                            Number</label>
                        <input type="text" wire:model="contact_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('contact_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company
                            Name</label>
                        <input type="text" wire:model="company_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('company_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="city_municipality"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City/Municipality</label>
                        <input type="text" wire:model="city_municipality"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('city_municipality')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                        <input type="text" wire:model="country"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- EVENT DETAILS --}}
            <div class="col-span-full md:col-span-2">
                <h3 class="block mb-4 text-lg font-semibold text-gray-900 dark:text-white">Event Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_datetime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                            Start
                            Date and Time</label>
                        <input type="datetime-local" wire:model.live="start_datetime" id="start_datetime"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('start_datetime')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="end_datetime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event End
                            Date and Time
                        </label>
                        <input type="datetime-local" wire:model.live="end_datetime" id="end_datetime"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('end_datetime')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="hall" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Event
                            Hall</label>
                        <select id="hall" wire:model="selected_hall"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">-- Choose a hall --</option>
                            @foreach ($halls as $hall)
                                <option value="{{ $hall->id }}" @if ($hall->isBooked) disabled @endif>
                                    {{ $hall->name_number }} @if ($hall->isBooked)
                                        - (Booked)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('selected_hall')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="event_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                            Type</label>
                        <select wire:model="event_type_id" id="event_type_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Select Type</option>
                            @foreach ($eventTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('event_type_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="total_adults" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max
                            Adults</label>
                        <input type="number" wire:model.live="total_adults" id="total_adults" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('total_adults')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="total_kids" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max
                            Kids</label>
                        <input type="number" wire:model.live="total_kids" id="total_kids" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('total_kids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="pax" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Pax</label>
                        <input type="number" id="pax" min="0" readonly value="{{ $pax }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('pax')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="mb-4">
                        <label for="transaction_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                            Status</label>
                        <select wire:model="transaction_status" id="transaction_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">-- Select Event Status -- </option>
                            <option value="confirmed">Confirmed</option>
                            <option value="ongoing">On-going</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="terminated">Terminated</option>
                        </select>
                        @error('transaction_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agreed Event
                            Amount</label>
                        <input type="amount" wire:model="total_amount" id="total_amount"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter agreed total amount">
                        @error('total_amount')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            {{-- FOR SCREENSHOT PAYMENT UPLOAD --}}
            {{--
            <div class="col-span-full md:col-span-2">
                <h3 class="block mb-4 text-lg font-semibold text-gray-900 dark:text-white">Payment Proof</h3>
                <div class="space-y-4">
                    <div class="mb-4">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload
                            Image</label>
                        <input multiple type="file" wire:model="images" id="image" accept="image/png, image/jpeg"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">

                        @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div wire:loading wire:target="images" class="flex items-center justify-center px-5">
                        <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                        </svg>
                        <span>Uploading...</span>
                    </div>

                    <div wire:loading.remove wire:target="images">
                        @if ($images && count($images) > 0)
                        @foreach ($images as $index => $image)
                        <div class="relative mb-4">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-contain rounded-lg shadow"
                                alt="Image Preview">
                            <button type="button" wire:click="removeImage({{ $index }})"
                                class="absolute top-2 right-2 bg-gray-300 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-sm">
                                ×
                            </button>
                        </div>
                        @endforeach
                        @else
                        <div
                            class="w-full h-48 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-gray-400 dark:border-gray-600 dark:text-gray-500">
                            No image selected
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            --}}
        </div>

        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-500">
                Cancel
            </x-button>
            <x-button wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                Add Event
            </x-button>
        </div>
    </form>

    <x-dialog-modal wire:model.live="confirmCreateItem">
        <x-slot name="title">
            {{ __('Create Event') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to add this event?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled"
                class="dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-500">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                wire:click="saveEvent" wire:loading.attr="disabled">
                {{ __('Create Event') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
