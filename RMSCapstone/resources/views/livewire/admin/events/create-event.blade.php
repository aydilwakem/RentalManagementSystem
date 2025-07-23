<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Event</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.events') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form Container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 md:grid-cols-2 sm:gap-6">

                    {{-- GUEST DETAILS --}}
                    <div class="col-span-full md:col-span-2">
                        <h3 class="block mb-2 text-xl font-bold text-green-800 dark:text-green-300">Booking Contact
                            Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="first_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="first_name" placeholder="Ex. Juan" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="middle_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Middle
                                    Name</label>
                                <input type="text" wire:model="middle_name" placeholder="Ex. Mercado"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('middle_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="email" required
                                    placeholder="Ex. juan.delacruz@example.com"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="contact_number"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                                    Number <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="contact_number" required placeholder="Ex. 0912 3456 7890"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('contact_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="company_name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="company_name" required
                                    placeholder="Ex. Event Management Inc."
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('company_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="country"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="country" wire:change="$refresh" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                    <option value="">Select Country</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('country')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Show only when "Other" is selected --}}
                            @if($country === 'Other')
                            <div>
                                <label for="otherCountry"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Please specify your country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="country" placeholder="Enter your country"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">

                                @error('country')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            @if ($country === 'Philippines')
                            <div>
                                <label for="city_municipality"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City /
                                    Municipality <span class="text-red-500">*</span></label>
                                <select id="city_municipality" wire:model="city_municipality" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    <option value="">Select Municipality</option>
                                    @foreach ($municipalities as $municipality)
                                    <option value="{{ $municipality->PSGC_MUNC_DESC }}">
                                        {{ $municipality->PSGC_MUNC_DESC }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('city_municipality')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                        </div>
                    </div>

                    {{-- EVENT DETAILS --}}
                    <div class="col-span-full md:col-span-2 mt-4">
                        <h3 class="block mb-2 text-xl font-bold text-green-800 dark:text-green-300">Event Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_datetime"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                                    Start
                                    Date and Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model.live="start_datetime" id="start_datetime"
                                    required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('start_datetime')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="end_datetime"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event End
                                    Date and Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" wire:model.live="end_datetime" id="end_datetime" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('end_datetime')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="hall"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                                    Hall <span class="text-red-500">*</span></label>
                                <select id="hall" wire:model="selected_hall" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    <option value="">Select Event Hall</option>
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
                                <label for="event_type_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                                    Type <span class="text-red-500">*</span></label>
                                <select wire:model="event_type_id" id="event_type_id" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    <option value="">Select Event Type</option>
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
                                <label for="total_adults"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max
                                    Adults <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.live="total_adults" id="total_adults" min="0"
                                    onwheel="this.blur()" required placeholder="Ex. 100 Adults"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('total_adults')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="total_kids"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max
                                    Kids </label>
                                <input type="number" wire:model.live="total_kids" id="total_kids" onwheel="this.blur()"
                                    placeholder="Ex. 50 Kids"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('total_kids')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="pax"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total
                                    Pax <span class="text-red-500">*</span></label>
                                <input type="number" id="pax" min="0" disabled value="{{ $pax }}" class="bg-gray-50 border border-gray-300 text-gray-400 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed
                             dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @error('pax')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div class="mb-4">
                                <label for="transaction_status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event
                                    Status <span class="text-red-500">*</span></label>
                                <select wire:model="transaction_status" id="transaction_status" required
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
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
                                <label for="total_amount"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agreed Event
                                    Cost <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="total_amount" id="total_amount" required class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600
                                    dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Ex. 50,000.00">
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
                                <label for="image"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload
                                    Image</label>
                                <input multiple type="file" wire:model="images" id="image"
                                    accept="image/png, image/jpeg"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">

                                @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div wire:loading wire:target="images" class="flex items-center justify-center px-5">
                                <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                                <span>Uploading...</span>
                            </div>

                            <div wire:loading.remove wire:target="images">
                                @if ($images && count($images) > 0)
                                @foreach ($images as $index => $image)
                                <div class="relative mb-4">
                                    <img src="{{ $image->temporaryUrl() }}"
                                        class="w-full h-48 object-contain rounded-lg shadow" alt="Image Preview">
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
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
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

                    <x-button
                        class="ms-3 bg-green text-white dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                        wire:click="saveEvent" wire:loading.attr="disabled">
                        {{ __('Create Event') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>