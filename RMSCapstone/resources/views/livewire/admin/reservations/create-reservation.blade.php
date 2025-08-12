<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-1 dark:text-white">
            {{ __('Create Reservation') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Reservations', 'url' => route('admin.reservations-list')],
            ['label' => 'Create Reservation', 'url' => route('admin.create-reservation')],
        ]" />
    </x-slot>

    <div class="relative flex items-center mb-3 mt-3">
        <h2 class="text-xl font-bold text-green-700 w-full text-center dark:text-green-300">
            Reservation Dates
        </h2>
        <!-- Back Button -->
        <button onclick="history.back()"
            class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full mt-2 w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
            <span class="leading-none translate-y-[-3px]">&times;</span>
        </button>
    </div>


    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!------------------------- ENTER DATES SECTION -------------------------->
        <!-- Check in and check out dates-->
        <div class="justify-center items-center text-center">
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-3">
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Check-In</label>
                    <input type="date" wire:model.live="check_in_date"
                        min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Check-in">
                </div>

                <h1 class="mt-4 dark:text-white"><i class="fas fa-arrow-right"></i></h1>

                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Check-Out</label>
                    <input type="date" wire:model.live="check_out_date"
                        min="{{ isset($check_in_date) ? \Carbon\Carbon::parse($check_in_date)->addDay()->format('Y-m-d') : \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d') }}"
                        class="w-full md:w-auto px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:border-green-500
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Check-out">
                </div>
            </div>

        </div>

        <!------------------------------ Reservation Date Details --------------------------->
        @php
        use Carbon\Carbon;
        @endphp

        <div class="bg-white shadow-md rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
            <h2
                class="font-bold text-xl text-green-700 text-center leading-tight mb-2 bg-green-50 py-3 rounded-t-lg shadow-sm dark:bg-green-200">
                Reservation Summary
            </h2>
            <div class="px-6 py-2">


                @if ($check_in_date)
                <div class="flex justify-center items-center text-md text-gray-800 space-x-4 dark:text-white">
                    <span>
                        {{ Carbon::parse($check_in_date)->format('F j, Y') }}
                    </span>

                    @error('check_in_date')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror

                    <i class="fa-solid fa-arrow-right"></i>

                    @if ($check_out_date)
                    <span>
                        {{ Carbon::parse($check_out_date)->format('F j, Y') }}
                    </span>
                    @endif
                </div>
                @endif

                @if ($check_out_date)
                <!-- Stay Duration -->
                <div class="flex justify-center items-center text-md text-gray-800 mb-4 space-x-4 dark:text-white">
                    <p class="text-center">Stay Duration: {{ $this->stayDuration }} night(s)</p>
                </div>
                @endif

                @if ($selectedRooms)
                <hr class="my-2 border-gray-200">

                <!-- Total Pax -->
                <p class=" text-gray-800 text-sm font-semibold mb-1 dark:text-white">No. of Guests:
                    {{ $this->total_pax }}</p>

                @if ($bringingPets)
                <!-- Total Pets -->
                <p class=" text-gray-800 text-sm font-semibold mb-1 dark:text-white">No.of Pets:
                    {{ $this->pet_count }}</p>
                @endif

                <!-- Total Room Charge -->
                <div class="flex justify-between items-center font-semibold text-gray-800 mb-1 dark:text-white">
                    <div class="text-sm">Room Subtotal: </div>
                    <div class="text-sm">₱{{ number_format($this->computeTotalAmountOfAllRooms(), 2) }}</div>
                </div>

                <!-- Total Activity Charge -->
                <div class="flex justify-between items-center font-semibold text-gray-800 mb-1 dark:text-white">
                    <div class="text-sm">Activity Subtotal: </div>
                    <div class="text-sm">₱{{ number_format($this->computeTotalAmountOfAllActivities(), 2) }}</div>
                </div>

                <!-- Total Services Charge -->
                <div class="flex justify-between items-center font-semibold text-gray-800 mb-1 dark:text-white">
                    <div class="text-sm">Services Subtotal: </div>
                    <div class="text-sm">₱{{ number_format($this->computeTotalAmountOfAllServices(), 2) }}</div>
                </div>

                <!-- Discount Code -->
                <hr class="my-2 border-gray-200">

                <!-- Subtotal Amount -->
                <div class="flex justify-between items-center font-semibold text-gray-800 mb-1 dark:text-white">
                    <div class="text-sm">Subtotal</div>
                    <div class="text-sm">₱{{ number_format($this->computeSubtotalAmount(), 2) }}</div>
                </div>

                <!-- Convenience Fee -->
                <div class="flex justify-between items-center font-semibold text-gray-800 mb-1 dark:text-white">
                    <div class="flex items-center space-x-2 text-sm">
                        <span>Convenience Fee</span>
                        <input type="checkbox" wire:model.live="apply_convenience_fee" class="form-checkbox">
                    </div>
                    <div class="text-sm">₱{{ number_format($this->computeConvenienceFee(), 2) }}</div>
                </div>

                <!-- Total Amount -->
                <div class="flex justify-between items-center font-semibold text-green-700 mb-1 dark:text-green-300">
                    <div class="text-lg">Total</div>
                    <div class="text-lg">₱{{ number_format($this->computeTotalAmount(), 2) }}</div>
                </div>

                <hr>

                <!-- Deposit -->
                @if ($enable_deposit_percentage && $this->deposit > 0)
                <!-- Deposit -->
                <div class="flex justify-between items-center text-sm text-gray-600 mb-3 dark:text-gray-100 mt-2">
                    <div>Required Deposit</div>
                    <div class="font-semibold">
                        ₱{{ number_format($this->deposit ?? 0, 2) }}</div>
                </div>
                @endif

                <hr>
                @if ($discountMessage)
                <p class="text-sm mt-1 text-green-600">{{ $discountMessage }}</p>
                @endif

                @if ($errorMessage)
                <p class="text-sm mt-1 text-red-500">{{ $errorMessage }}</p>
                @endif

                @php
                $hasCode = !empty($promoCode) && empty($discountMessage) === false;
                @endphp

                <div class="flex justify-center">
                    <div class="relative max-w-xl mt-4 mb-2 flex justify-center ">
                        <input type="text" wire:model="promoCode"
                            wire:key="promo-code-{{ $hasCode ? 'applied' : 'empty' }}"
                            class="border rounded-md px-4 py-2 w-full pr-16 shadow-sm transition focus:outline-none focus:ring-1
                                {{ $hasCode ? 'border-green-500 ring-green-500 bg-green-50 text-green-800 font-semibold' : 'border-gray-300 focus:ring-green-500 focus:border-green-500' }}"
                            placeholder="Enter Promo Code" autocomplete="off" {{ $hasCode ? 'disabled' : '' }} {{--
                            optional: disable when applied --}}>

                        {{-- TODO: disable field when code is inputted, clear field when removed --}}
                        @if ($discountMessage)
                        <button wire:key="remove-promo-button" type="button" wire:click="removePromoCode"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-red-600 text-md font-medium focus:outline-none"
                            title="Remove Promo Code">
                            &times;
                        </button>
                        @else
                        <button wire:key="apply-promo-button" type="button" wire:click="applyPromoCode"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-green-600 text-sm font-medium hover:underline focus:outline-none">
                            Apply
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Empty message -->
                @if (!$check_in_date && !$check_out_date && !$selectedRooms)
                <div class="text-center text-gray-500 mt-6 mb-6">
                    No reservation details available yet.
                </div>
                @endif

                {{-- <button wire:click="debug"
                    class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    + Debug
                </button> --}}
            </div>
        </div>


        <!---------------------- ROOM AND ACTIVITY CART -------------------------->


        <!---------------------------- ROOM DETAILS ------------------------------->
        <!-- Room Cart Table (Top) -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-200">
                    Accomodations
                </h2>
                <!-- Add Room Button -->
                <div class="flex justify-end">
                    <x-button wire:click="openModal('room')" icon="fas fa-plus">
                        Add Room
                    </x-button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm text-left dark:border-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="text-center">
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Room Name</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                No. of Adults
                            </th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                No. of Kids</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Extra Guests</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Base Rate</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Extra Guest Charge
                            </th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                Total</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 text-center dark:text-gray-200 dark:border-gray-500">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-600">
                        @forelse($selectedRooms as $room)
                        <tr class="text-center">
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $room['room_name'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $room['adults'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $room['kids'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $room['extra_guest'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                ₱{{ number_format($room['roomAmount'], 2) }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                ₱{{ number_format($room['extra_charge_total'], 2) }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                ₱{{ number_format($room['total_amount'], 2) }}</td>
                            <td
                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 space-x-3">
                                <button wire:click="editSelectedRoom({{ $room['room_id'] }})"
                                    class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button wire:click="RemoveRoom({{ $room['room_id'] }})"
                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                                No rooms added yet.
                            </td>
                        </tr>
                        @endforelse


                    </tbody>
                </table>
                <div class="text-right font-semibold text-base mt-2 text-gray-700 dark:text-white">
                    Total Room Charges: ₱{{ number_format($this->computeTotalAmountOfAllRooms(), 2) }}
                </div>
            </div>
        </div>

        <!---------------------------- ACTIVITY DETAILS ------------------------------->
        <!-- Activity Cart Table (Bottom) -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-200">
                    Activities
                </h2>
                <!-- Add Room Button -->
                <div class="flex justify-end">
                    <x-button wire:click="openModal('activity')" icon="fas fa-plus">
                        Add Activity
                    </x-button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300 text-sm text-left dark:border-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="text-center">
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Activity Name</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Scheduled Time</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Price</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Quantity</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Total Amount</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-600">
                        @forelse($selectedActivities as $activity)
                        <tr class="text-center">
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $activity['activity_name'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                @if (!empty($activity['activity_datetime']))
                                {{ \Carbon\Carbon::parse($activity['activity_datetime'])->format('g:i A') }}
                                @else
                                No schedule
                                @endif</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $activity['activity_rate'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $activity['quantity'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $activity['amount'] }}</td>
                            <td
                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 space-x-3">
                                <button wire:click="editSelectedActivity({{ $activity['activity_id'] }})"
                                    class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="RemoveActivity({{ $activity['activity_id'] }})"
                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                                No activities added yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-right font-semibold text-base mt-2 text-gray-700 dark:text-white">
                    Total Activity Charges: ₱{{ number_format($this->computeTotalAmountOfAllActivities(), 2) }}
                </div>
            </div>
        </div>

        <!---------------------------- SERVICES/CHARGES DETAILS ------------------------------->
        <!-- Services/Charges Cart Table (Bottom) -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-200">
                    Additional Charges
                </h2>
                <!-- Add Service Button -->
                <div class="flex justify-end">
                    <x-button wire:click="openModal('services')" icon="fas fa-plus">
                        Add Charges
                    </x-button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300 text-sm text-left dark:border-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="text-center">
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Service Name</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Price</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Quantity</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Total Amount</th>
                            <th
                                class="border px-4 py-2 font-medium text-gray-900 dark:text-gray-200 dark:border-gray-500">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-gray-600">
                        @forelse($selectedServices as $service)
                        <tr class="text-center">
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $service['service_name'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $service['service_rate'] }} / {{ $service['service_unit'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $service['quantity'] }}</td>
                            <td class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $service['amount'] }}</td>
                            <td
                                class="border px-4 py-2 text-gray-700 dark:text-gray-200 dark:border-gray-500 space-x-3">
                                <button wire:click="editSelectedService({{ $service['service_id'] }})"
                                    class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-500">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="RemoveService({{ $service['service_id'] }})"
                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                                No services/charges added yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="text-right font-semibold text-base mt-2 text-gray-700 dark:text-white">
                    Total Services Charges: ₱{{ number_format($this->computeTotalAmountOfAllServices(), 2) }}
                </div>
            </div>
        </div>



        <!------------------------- GUEST DETAIL SECTION ------------------------->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <h2 class="font-semibold text-xl text-green-700 leading-tight dark:text-green-200">
                Guest Details
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4 mb-4">
                <!-- First Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">First Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="first_name" placeholder="Ex. Juan" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('first_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Middle Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Middle Name</label>
                    <input type="text" wire:model="middle_name" placeholder="Ex. Mercado" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('middle_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Last Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('last_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" wire:model="email" placeholder="Ex. juan.delacruz@example.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Contact Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="contact_number" placeholder="Ex. 09123456789" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('contact_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Country -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                            class="text-red-500">*</span></label>
                    <select wire:model="country" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="" disabled selected>Select a country</option>
                        @foreach ($countries as $countryOption)
                        <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                        @endforeach
                    </select>
                    @error('country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Name -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Company Name</label>
                    <input type="text" wire:model="company_name" placeholder="Ex. ABC Corporation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <!-- Source of Hearing -->
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Heard From <span
                            class="text-red-500">*</span></label>
                    <select wire:model="heard_from" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select an option</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Tiktok">Tiktok</option>
                        <option value="Youtube">Youtube</option>
                        <option value="Google">Google</option>
                    </select>
                    @error('heard_from')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <!-- Source of Booking -->
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Reservation
                        Source</label>
                    <select wire:model="reservation_source" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select an option</option>
                        <option value="AirBnb">AirBnb</option>
                        <option value="Website">Website</option>
                        <option value="Facebook Messenger">Facebook Messenger</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Walk-In">Walk-In</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('reservation_source')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Special Requests -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">
                        Special Requests <span class="text-xs text-gray-500 dark:text-gray-400">(subject to
                            approval)</span>
                    </label>

                    @foreach ($special_requests as $index => $request)
                    <div class="mb-2 flex items-center gap-2">
                        <input type="text" wire:model="special_requests.{{ $index }}.request"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            placeholder="Enter request" />
                        <button wire:click.prevent="removeSpecialRequest({{ $index }})"
                            class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                    </div>
                    @error("special_requests.$index.request")
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    @endforeach

                    <button wire:click.prevent="addSpecialRequest"
                        class="mt-2 text-sm text-green-600 hover:text-green-800">+ Add Request</button>
                </div>


                @if ($bringingPets)
                {{-- <div class="col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Pets included in this reservation
                    </label>
                </div> --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">
                        Pet Details (Total: {{ $pet_count }})
                    </label>

                    @foreach ($pets as $index => $pet)
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Pet #{{ $index + 1 }} Breed
                        </label>
                        <input type="text" wire:model="pets.{{ $index }}.breed"
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                            placeholder="e.g., Labrador">

                        <button type="button" wire:click="removeGuestPet({{ $index }})"
                            class="text-red-500 mt-1 text-sm">Remove</button>
                    </div>
                    @endforeach

                    {{-- General error for pets --}}
                    @error('pets.*.breed')
                    <div class="text-red-600 text-sm mt-2">Please enter the breed for all pets.</div>
                    @enderror
                </div>
                @endif









            </div>
        </div>


        <!-- Additional Guests Section (Optional) -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex flex-col space-y-2 w-full">
                <div class="font-semibold  text-xl text-green-700 dark:text-green-200">
                    Additional Guests (Optional)
                </div>

                <!-- Button to open modal -->

                <div class="mt-4">
                    <x-button type="button" wire:click="openModal('guest')" icon="fas fa-plus">
                        Add Guest
                    </x-button>
                </div>


                @error('guests')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror

                <!-- Displaying Added Guests -->
                <div class="mt-6">
                    @if (count($guests) > 0)
                    <ol
                        class="space-y-3 list-decimal pl-6 text-gray-700 mb-3 p-3 bg-white rounded-2xl border border-gray-300 dark:bg-gray-600 dark:border-gray-500">
                        @foreach ($guests as $guest)
                        <li class="me-2 dark:text-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-gray-700 flex dark:text-gray-200">
                                    {{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}
                                </div>
                                <div class="space-x-5 flex items-center">
                                    <button wire:click="editGuest({{ $loop->index }})" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 hover:underline font-sm transition duration-150
                                                dark:text-indigo-400 dark:hover:text-indigo-600">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </button>

                                    <button wire:click="deleteGuest({{ $loop->index }})" class="inline-flex items-center text-red-500 hover:text-red-700 hover:underline font-sm transition duration-150
                                                dark:text-red-400 dark:hover:text-red-600">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                    @else
                    <p class="text-center text-gray-500 mt-6 mb-6 dark:text-gray-200">No guests added yet.</p>
                    @endif
                </div>

            </div>
        </div>

        <!------------------------- MODALS SECTION ------------------------->

        <!-- Add Room Modal -->
        @if ($roomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden dark:bg-gray-700">

                @error('selectedRooms')
                <span class="text-red-600">{{ $message }}</span>
                @enderror

                <!-- Header -->
                <div
                    class=" bg-green-50 flex justify-between items-center border-b border-gray-200 px-6 py-4 dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-green-700 dark:text-green-200">Choose Rooms</h2>

                    <button wire:click="$set('roomModal', false)"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none dark:text-gray-200 dark:hover:text-gray-400">
                        &times;
                    </button>
                </div>

                <!-- Body / Room List -->
                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                    @if ($rooms->count() === 0)
                    <div class="w-full flex justify-center">
                        <div class="step-one w-full px-4">
                            <div
                                class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0 dark:bg-gray-500 dark:border-gray-400">
                                <div class="p-4 text-center">
                                    <h4 class="text-2xl font-semibold mb-2 dark:text-gray-200">No Rooms
                                        Available</h4>
                                    <p class="text-base font-normal text-gray-700 dark:text-gray-200">
                                        Sorry, there are no rooms available for the selected dates.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        @foreach ($rooms as $room)

                        @php
                        $isSelected = collect($selectedRooms)->contains('room_id', $room->id);
                        @endphp

                        @if ($isSelected)
                        @continue
                        @endif

                        <div wire:key="room-{{ $room->id }}"
                            class="bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-6 dark:bg-gray-500 dark:border-gray-400">
                            <div class="flex flex-col md:flex-row md:space-x-6">

                                <!-- Room Info -->
                                <div class="md:w-2/3 space-y-2">
                                    <h4 class="text-xl font-semibold text-gray-800 dark:text-white">
                                        {{ $room->name_number }}</h4>

                                    <p class="text-sm text-gray-600 flex items-center dark:text-gray-200">
                                        <i class="fas fa-user mr-2 text-gray-500 dark:text-gray-300"></i>
                                        Ideal Guests:
                                        {{ $room->ideal_guest }}
                                    </p>

                                    @if ($room->occupancy_type === 'whole_number')
                                    <p class="text-sm text-gray-600 flex items-center dark:text-gray-200">
                                        <i class="fas fa-users mr-2 text-gray-500 dark:text-gray-300"></i>
                                        Maximum Capacity: {{ $room->max_guests }} guests
                                    </p>
                                    @elseif ($room->occupancy_type === 'combinations')
                                    @php
                                    $originalCombinations = collect($room->occupancy_rules)->where(
                                    'type',
                                    'original',
                                    );

                                    $formatted = $originalCombinations->map(function ($combo) {
                                    $parts = [];

                                    if (!empty($combo['adults'])) {
                                    $parts[] =
                                    $combo['adults'] .
                                    ' adult' .
                                    ($combo['adults'] > 1 ? 's' : '');
                                    }

                                    if (!empty($combo['kids'])) {
                                    $parts[] =
                                    $combo['kids'] .
                                    ' kid' .
                                    ($combo['kids'] > 1 ? 's' : '');
                                    }

                                    return implode(' and ', $parts);
                                    });
                                    @endphp

                                    @if ($formatted->isNotEmpty())
                                    <p class="text-sm text-gray-600 flex items-center dark:text-gray-200">
                                        <i class="fas fa-users mr-2 text-gray-500 dark:text-gray-300"></i>
                                        Max Occupancy: {{ $formatted->implode(' / ') }}
                                    </p>
                                    @endif
                                    @endif

                                    <p class="text-sm text-gray-600 flex items-center dark:text-gray-200">
                                        <i class="fas fa-plus mr-2 text-gray-500 dark:text-gray-300"></i>
                                        Extra Person Charge:
                                        ₱{{ number_format($room->extra_person_charge, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-600 flex items-center dark:text-gray-200">
                                        <i class="fas fa-utensils mr-2 text-gray-500 dark:text-gray-300"></i>
                                        Free breakfast
                                        included
                                    </p>
                                    <p class="text-xs italic text-gray-500 mt-1 dark:text-gray-200">
                                        {{ $room->description }}
                                    </p>

                                    <p class="mt-3 text-base font-medium dark:text-gray-900">
                                        Rate Per Night:
                                        @if ($room->rate_name || $room->rate_type)
                                        <span class="text-green-700 font-bold mb-1">
                                            ₱{{ number_format($room->dynamic_rate, 2) }}
                                        </span>
                                        <span class="inline-block py-1 px-2 rounded-full text-xs font-semibold mb-4
                                                            @if ($room->rate_type === 'Weekend') bg-yellow-100 text-yellow-700
                                                            @elseif ($room->rate_type === 'Weekdays') bg-green-100 text-green-700
                                                            @elseif ($room->rate_type === 'Peak') bg-red-100 text-red-700
                                                            @elseif ($room->rate_type === 'Holiday') bg-purple-100 text-purple-700
                                                            @else bg-gray-100 text-gray-600 @endif">
                                            {{ $room->rate_name }}
                                            @if ($room->rate_type)
                                            - {{ $room->rate_type }} Rate
                                            @endif
                                        </span>
                                        @else
                                        <span class="text-red-600 text-sm">No rate info
                                            available</span>
                                        @endif
                                    </p>


                                </div>

                                <!-- Booking Controls -->
                                <div class="md:w-1/3 flex flex-col justify-between mt-4 md:mt-0 space-y-4">

                                    <div class="flex gap-4">

                                        <!-- Adults -->
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 me-3">Adults</label>
                                            <select wire:model.live="adults.{{ $room->id }}"
                                                wire:change="updateKidOptions({{ $room->id }})"
                                                class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                @foreach ($room->availableAdultOptions ?? [] as $adult)
                                                <option value="{{ $adult }}">
                                                    {{ $adult }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Kids -->
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700">Children</label>
                                            <select wire:model.live="kids.{{ $room->id }}"
                                                class="mt-1 block w-full border border-gray-300 rounded px-2 py-1">
                                                @foreach ($dynamicKidOptions[$room->id] ?? ($room->availableKidOptions
                                                ?? []) as $kid)
                                                <option value="{{ $kid }}">
                                                    {{ $kid }}</option>
                                                @endforeach
                                            </select>
                                        </div>




                                    </div>


                                    <!-- Add Room Button -->
                                    @php
                                    $isSelected = collect($selectedRooms)->contains('room_id', $room->id);
                                    @endphp

                                    @if ($room->is_booked)
                                    <button disabled
                                        class="w-full px-4 py-2 bg-gray-400 text-white font-semibold rounded-md text-xs transition ease-in-out duration-150 uppercase cursor-not-allowed">
                                        <div class="flex items-center justify-center">
                                            <span>Unavailable</span>
                                        </div>
                                    </button>
                                    @else
                                    <button
                                        wire:click="{{ $isSelected ? 'RemoveRoom' : 'SelectedRooms' }}({{ $room->id }})"
                                        class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-xs transition ease-in-out duration-150 uppercase"
                                        wire:loading.attr="disabled">
                                        <div class="flex items-center justify-center">
                                            <span wire:loading wire:target="SelectedRooms({{ $room->id }})"
                                                class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <span wire:loading.remove
                                                wire:target="{{ $isSelected ? 'RemoveRoom' : 'SelectedRooms' }}({{ $room->id }})">
                                                {{ $isSelected ? 'Remove Room' : 'Add Room' }}
                                            </span>
                                        </div>
                                    </button>
                                    @endif



                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Add Activity Modal -->
        @if ($activityModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden dark:bg-gray-700">

                @error('selectedActivities')
                <span class="text-red-600">{{ $message }}</span>
                @enderror

                <!-- Header -->
                <div
                    class="flex justify-between items-center border-b bg-green-50 border-gray-200 px-6 py-4 dark:border-gray-500 dark:bg-gray-800">
                    <h2 class="text-2xl font-semibold text-green-700 dark:text-green-300">Choose Activities</h2>

                    <button wire:click="$set('activityModal', false)"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none dark:text-gray-200 dark:hover:text-gray-400">
                        &times;
                    </button>
                </div>

                <!-- Body / Room List -->
                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                    @if ($activities->count() === 0)
                    <div class="w-full flex justify-center">
                        <div class="step-one w-full px-4">
                            <div
                                class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                                <div class="p-4 text-center">
                                    <h4 class="text-2xl font-semibold mb-2 dark:text-gray-200">No Activities
                                        Available</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        @foreach ($activities as $activity)

                        @php
                        $isSelected = collect($selectedActivities)->contains('activity_id', $activity->id);
                        @endphp

                        @if ($isSelected)
                        @continue
                        @endif

                        <div wire:key="activity-{{ $activity->id }}"
                            class="flex items-center justify-between bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-4 dark:bg-gray-500 dark:border-gray-400">

                            <!-- Left: Image -->
                            @php
                            $firstImage = is_array(json_decode($activity->image))
                            ? json_decode($activity->image)[0] ?? null
                            : null;
                            @endphp

                            <div
                                class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                <img src="{{ asset($firstImage ? 'storage/' . $firstImage : 'images/rms-default.png') }}"
                                    alt="{{ $activity->name }}" class="object-cover w-full h-full">
                            </div>


                            <!-- Middle: Name and Description -->
                            <div class="flex-1 px-8">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-white">
                                    {{ $activity->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1 dark:text-gray-200 text-justify">
                                    {{-- Show more / less when description is long --}}
                                    @if (empty($activity->description))
                                    <span class="italic text-gray-400">No description provided</span>
                                    @elseif ($expandedActivity === $activity->id)
                                    {{ $activity->description }}
                                    <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                        class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                        less</a>
                                    @else
                                    {{ Str::limit($activity->description, 100, '...') }}
                                    @if (Str::length($activity->description) > 100)
                                    <a href="#" wire:click.prevent="toggleActivityDescription({{ $activity->id }})"
                                        class="text-gray-600 hover:underline ml-1 dark:text-gray-200">Show
                                        more</a>
                                    @endif
                                    @endif
                                </p>
                            </div>

                            <!-- Preferred Time -->
                            @if ($activity->schedule_type !== 'no_schedule')
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2 dark:text-gray-200">Preferred Time
                                </h3>

                                @if ($activity->schedule_type === 'system')
                                @if (is_array($activity->available_times) && count($activity->available_times))
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach ($activity->available_times as $time)
                                    <label
                                        class="flex items-center p-2 bg-white dark:bg-gray-700 border border-gray-300 rounded cursor-pointer shadow-sm hover:border-green-500">
                                        <input type="radio" name="selected_time_{{ $activity->id }}"
                                            wire:model="selectedTimes.{{ $activity->id }}" value="{{ $time }}"
                                            class="form-radio text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-800 dark:text-gray-200">
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-gray-500 italic">No system-defined schedule for this activity.
                                </p>
                                @endif

                                @elseif ($activity->schedule_type === 'guest')
                                <input type="time" wire:model.lazy="selectedTimes.{{ $activity->id }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                @endif

                                @error("selectedTimes.{$activity->id}")
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            @endif

                            <!-- Right: Price and Quantity -->
                            <div class="flex flex-col md:flex-row md:space-x-6">
                                <div class="flex flex-col">
                                    <label for="quantity-{{ $activity->id }}"
                                        class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200 text-center">
                                        Quantity:
                                    </label>

                                    <!-- Counter Buttons -->
                                    <div class="flex items-center">
                                        <button type="button"
                                            wire:click.prevent="decrementActivity('{{ $activity->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline
                                                    dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            -
                                        </button>

                                        <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded ">
                                            {{ $quantity[$activity->id] ?? 1 }}
                                        </span>

                                        @if ($quantity[$activity->id] ?? 1)
                                        <button type="button"
                                            wire:click.prevent="incrementActivity('{{ $activity->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline
                                                        dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            +
                                        </button>
                                        @endif
                                    </div>
                                </div>


                                <!-- Add Activity Button -->
                                <div class=" flex flex-col justify-between mt-4 py-2 space-y-4">
                                    @php
                                    $isSelected = collect($selectedActivities)->contains(
                                    'activity_id',
                                    $activity->id,
                                    );
                                    @endphp

                                    <button
                                        wire:click="{{ $isSelected ? 'RemoveActivity' : 'SelectedActivities' }}({{ $activity->id }})"
                                        class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-xs transition ease-in-out duration-150 uppercase"
                                        wire:loading.attr="disabled">

                                        <!-- Spinner -->
                                        <div class="flex items-center justify-center">
                                            <span wire:loading wire:target="SelectedActivities({{ $activity->id }})"
                                                class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <!-- Button Text -->
                                            <span wire:loading.remove
                                                wire:target="{{ $isSelected ? 'RemoveActivity' : 'SelectedActivities' }}({{ $activity->id }})">
                                                {{ $isSelected ? 'Remove' : 'Add' }}
                                            </span>
                                        </div>
                                    </button>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>
        </div>
        @endif

        <!-- Add Services/Charge Modal -->
        @if ($servicesModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 overflow-hidden dark:bg-gray-700">

                @error('selectedServices')
                <span class="text-red-600">{{ $message }}</span>
                @enderror

                <!-- Header -->
                <div
                    class="flex justify-between bg-green-50 items-center border-b border-gray-200 px-6 py-4 dark:border-gray-500 dark:bg-gray-800">
                    <h2 class="text-2xl font-semibold text-green-700 dark:text-green-300">Choose Services/Charge
                    </h2>

                    <button wire:click="$set('servicesModal', false)"
                        class="text-gray-500 hover:text-gray-700 text-2xl font-bold focus:outline-none dark:text-gray-200 dark:hover:text-gray-400">
                        &times;
                    </button>
                </div>

                <!-- Body / Services List -->
                <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">
                    @if ($services_charges->count() === 0)
                    <div class="w-full flex justify-center">
                        <div class="step-one w-full px-4">
                            <div
                                class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition mb-0">
                                <div class="p-4 text-center">
                                    <h4 class="text-2xl font-semibold mb-2 dark:text-gray-200">No Services
                                        Available</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        @foreach ($services_charges as $service)

                        @php
                        $isSelected = collect($selectedServices)->contains('service_id', $service->id);
                        @endphp

                        @if ($isSelected)
                        @continue
                        @endif

                        <div wire:key="service-{{ $service->id }}"
                            class="flex items-center justify-between bg-gray-50 border rounded-xl shadow-sm hover:shadow-md transition p-4 mb-4 dark:bg-gray-500 dark:border-gray-400">

                            <!-- Middle: Name and Description -->
                            <div class="flex-1 px-4 py-2">
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ $service->name }}
                                    </h4>
                                    @if (empty($service->description))
                                    <span class="italic text-gray-400 dark:text-gray-500">No description
                                        provided</span>
                                    @elseif ($expandedService === $service->id)
                                    <span>{{ $service->description }}</span>
                                    <a href="#" wire:click.prevent="toggleServiceDescription({{ $service->id }})"
                                        class="ml-1 text-blue-600 hover:underline dark:text-blue-400">
                                        Show less
                                    </a>
                                    @else
                                    <span>{{ Str::limit($service->description, 100, '...') }}</span>
                                    @if (Str::length($service->description) > 100)
                                    <a href="#" wire:click.prevent="toggleServiceDescription({{ $service->id }})"
                                        class="ml-1 text-blue-600 hover:underline dark:text-blue-400">
                                        Show more
                                    </a>
                                    @endif
                                    @endif
                                </div>
                                <p class="text-base font-2xl font-medium text-green-600 dark:text-green-400 mt-2">
                                    ₱{{ number_format($service->amount, 2) }}
                                </p>
                            </div>


                            <!-- Right: Price and Quantity -->
                            <div class="flex flex-col md:flex-row md:space-x-12">
                                <div class="flex flex-col">
                                    <label for="quantity-{{ $service->id }}"
                                        class="text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">
                                        Quantity:
                                    </label>

                                    <!-- Counter Buttons -->
                                    <div class="flex items-center">
                                        <button type="button"
                                            wire:click.prevent="decrementService('{{ $service->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-2 py-1 focus:outline-none focus:shadow-outline
                                                    dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            -
                                        </button>

                                        <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded ">
                                            {{ $quantity[$service->id] ?? 1 }}
                                        </span>

                                        @if ($quantity[$service->id] ?? 1)
                                        <button type="button"
                                            wire:click.prevent="incrementService('{{ $service->id }}')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-2 py-1 focus:outline-none focus:shadow-outline
                                                        dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                            +
                                        </button>
                                        @endif
                                    </div>
                                </div>


                                <!-- Add Service Button -->
                                <div class=" flex flex-col justify-between mt-4 space-y-4">
                                    @php
                                    $isSelected = collect($selectedServices)->contains(
                                    'service_id',
                                    $service->id,
                                    );
                                    @endphp

                                    <button
                                        wire:click="{{ $isSelected ? 'RemoveService' : 'SelectedServices' }}({{ $service->id }})"
                                        class="w-full px-4 py-2 {{ $isSelected ? 'bg-red-600 hover:bg-red-700' : 'bg-green-700 hover:bg-green-800' }} text-white font-semibold rounded-md text-sm transition ease-in-out duration-150 uppercase"
                                        wire:loading.attr="disabled">

                                        <!-- Spinner -->
                                        <div class="flex items-center justify-center">
                                            <span wire:loading wire:target="SelectedServices({{ $service->id }})"
                                                class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4">
                                                    </circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <!-- Button Text -->
                                            <span wire:loading.remove
                                                wire:target="{{ $isSelected ? 'RemoveService' : 'SelectedServices' }}({{ $service->id }})">
                                                {{ $isSelected ? 'Remove' : 'Add' }}
                                            </span>
                                        </div>



                                    </button>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Add Guest Modal -->
        @if ($guestModal)
        <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div
                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto dark:bg-gray-700">
                <h2 class="text-lg font-semibold mb-4 text-green-700 dark:text-green-300">Enter Guest Details</h2>

                <!-- Guest Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">First Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="guest_first_name" placeholder="Ex. Juan"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600" required>
                        @error('guest_first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Middle
                            Name</label>
                        <input type="text" wire:model="guest_middle_name" placeholder="Ex. Mercado"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                        @error('guest_middle_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Last Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="guest_last_name" placeholder="Ex. Dela Cruz"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600" required>
                        @error('guest_last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="bblock text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Suffix</label>
                        <input type="text" wire:model="guest_suffix" placeholder="Ex. Jr., Sr., III"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                        @error('guest_suffix')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Guest Type -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Guest Type <span
                            class="text-red-500">*</span></label>
                    <select wire:model="guest_type_id"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                        <option value="">Select Guest Type</option>
                        @foreach ($guest_types as $type)
                        <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                        @endforeach
                    </select>
                    @error('guest_type_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Gender -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Gender <span
                            class="text-red-500">*</span></label>
                    <select wire:model="guest_gender"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Prefer not to say</option>
                    </select>
                    @error('guest_gender')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Residency Status-->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Residency Status<span
                            class="text-red-500">*</span></label>
                    <select wire:model="guest_residency"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none focus:ring-green-600 focus:border-green-600">
                        <option value="">Select Residency Status</option>
                        <option value="local">Local</option>
                        <option value="foreigner">Foreigner</option>
                    </select>
                    @error('guest_residency')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                            class="text-red-500">*</span></label>
                    <select wire:model="guest_country_of_origin" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="" disabled>Select a country</option>
                        @foreach ($countries as $countryOption)
                        <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                        @endforeach
                    </select>
                    @error('guest_country_of_origin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center gap-2 mt-6">
                    <x-button type="button" wire:click="$set('guestModal', false)"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>
                    <x-button type="button" wire:click="addMultipleGuests">
                        Add Guest
                    </x-button>
                </div>
            </div>
        </div>
        @endif

        <!-- Edit Guest Modal -->
        @if ($editGuestModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                <h2 class="text-lg font-semibold mb-4">Edit Guest Details</h2>

                <!-- Guest Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200">First Name<span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="editingGuest.guest_first_name" placeholder="Ex. Juan"
                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                        @error('editingGuest.guest_first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200">Middle Name</label>
                        <input type="text" wire:model.defer="editingGuest.guest_middle_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        @error('editingGuest.guest_middle_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200">Last Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.defer="editingGuest.guest_last_name" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                        @error('editingGuest.guest_last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 dark:text-gray-200">Suffix</label>
                        <input type="text" wire:model.defer="editingGuest.guest_suffix" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                                dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        @error('editingGuest.guest_suffix')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Guest Type -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Guest Type <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="editingGuest.guest_type_id" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select Guest Type</option>
                        @foreach ($guest_types as $type)
                        <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                        @endforeach
                    </select>
                    @error('editingGuest.guest_type_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Gender -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Gender <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="editingGuest.guest_gender" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Prefer not to say</option>
                    </select>
                    @error('editingGuest.guest_gender')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Residency -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Residency <span
                            class="text-red-500">*</span></label>
                    <select wire:model.defer="editingGuest.guest_residency" class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md
                            dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Select Residency</option>
                        <option value="local">Local</option>
                        <option value="foreigner">Foreigner</option>
                    </select>
                    @error('editingGuest.guest_residency')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country of Origin -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700 dark:text-gray-200">Country of Origin <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="editingGuest.guest_country_of_origin
                        dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                    @error('editingGuest.guest_country_of_origin')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 mt-6">
                    <x-ghost-button wire:click="$set('editGuestModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateGuest">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </div>
        @endif

        <!-- Edit Service Modal -->
        @if ($showEditServiceModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit Service Quantity</h2>
                </div>

                <!-- Counter Buttons -->
                <div class="flex items-center justify-center">
                    <button type="button" wire:click.prevent="decrementSelectedService('{{ $editingServiceId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        -
                    </button>

                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                        {{ $serviceQuantity }}
                    </span>

                    <button type="button" wire:click.prevent="incrementSelectedService('{{ $editingServiceId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        +
                    </button>
                </div>

                <div class="flex justify-between mt-5">
                    <x-ghost-button wire:click="$set('showEditServiceModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateService">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
        @endif

        <!-- Edit Activity Modal -->
        @if ($showEditActivityModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit {{ $activityName }} Quantity</h2>
                </div>

                <!-- Counter Buttons -->
                <div class="flex items-center justify-center">
                    <button type="button" wire:click.prevent="decrementSelectedActivity('{{ $editingActivityId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        -
                    </button>

                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                        {{ $activityQuantity }}
                    </span>

                    <button type="button" wire:click.prevent="incrementSelectedActivity('{{ $editingActivityId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        +
                    </button>
                </div>

                <div class="flex justify-between mt-5">
                    <x-ghost-button wire:click="$set('showEditActivityModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateActivity">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
        @endif

        <!-- Edit Activity Modal -->
        @if ($showEditActivityModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit {{ $activityName }} Quantity</h2>
                </div>

                <!-- Counter Buttons -->
                <div class="flex items-center justify-center">
                    <button type="button" wire:click.prevent="decrementSelectedActivity('{{ $editingActivityId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        -
                    </button>

                    <span class="text-center w-16 py-1 bg-white border border-gray-300 rounded">
                        {{ $activityQuantity }}
                    </span>

                    <button type="button" wire:click.prevent="incrementSelectedActivity('{{ $editingActivityId }}')"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-3 py-1 focus:outline-none dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                        +
                    </button>
                </div>


                @if ($activityScheduleType !== 'no_schedule')
                <!-- Edit Time Section -->
                <div class="flex flex-col items-start justify-center">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Preferred Time
                    </label>

                    @if ($activityScheduleType === 'guest')
                    <input type="time" id="activityTime" wire:model.lazy="activityDateTime"
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    @elseif ($activityScheduleType === 'system' && !empty($availableTimes))
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($availableTimes as $option)
                        <label
                            class="inline-flex items-center p-2 bg-white dark:bg-gray-700 border rounded cursor-pointer">
                            <input type="radio" wire:model="activityDateTime" value="{{ $option }}"
                                class="form-radio text-green-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($option)->format('h:i A') }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif





                <div class="flex justify-between mt-5">
                    <x-ghost-button wire:click="$set('showEditActivityModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateActivity">
                        Update
                    </x-button>
                </div>
            </div>
        </div>
        @endif

        <!-- Edit Room Modal -->
        @if ($showEditRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                <div
                    class="relative -mt-6 -mx-6 mb-4 bg-green-50 text-green-700 py-3 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Edit {{ $roomName }} Quantity</h2>
                </div>

                <!-- Counter Buttons -->
                <div class="items-center justify-center flex flex-col">
                    <div class="mb-4">
                        <label class="block mb-1 text-center">Adults</label>
                        <div class="flex items-center">
                            <button type="button" wire:click="decrementAdults"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">−</button>
                            <input type="number" wire:model="roomTotalAdults" min="0"
                                class="w-16 text-center border rounded px-2 py-1">
                            <button type="button" wire:click="incrementAdults"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">+</button>
                        </div>
                        @error('roomTotalAdults')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-center">Kids</label>
                        <div class="flex items-center">
                            <button type="button" wire:click="decrementKids"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">−</button>
                            <input type="number" wire:model="roomTotalKids" min="0"
                                class="w-16 text-center border rounded px-2 py-1">
                            <button type="button" wire:click="incrementKids"
                                class="px-3 py-1 bg-gray-200 rounded text-lg">+</button>
                        </div>
                        @error('roomTotalKids')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-5">
                    <x-ghost-button wire:click="$set('showEditRoomModal', false)">
                        Cancel
                    </x-ghost-button>
                    <x-button wire:click="updateRoom">
                        Update
                    </x-button>
                </div>
            </div>

        </div>
        @endif
    </div>


    <!-- Can't add activity modal -->
    @if ($addRoomFirstModal)
    <x-dialog-modal wire:model.live="addRoomFirstModal" type="ghost">
        <x-slot name="title">
            {{ __('No Rooms Selected Yet') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Please add one or more rooms first.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('addRoomFirstModal', false)" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    @endif


    <!------------------------- BUTTON TO SUBMIT ------------------------->
    <!-- Actions Buttons -->
    <div class="flex justify-between items-center space-y-2 py-5">
        <x-button onclick="history.back()" type="button"
            class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
            Cancel
        </x-button>

        <x-button type="button" wire:click="CreateReservation" wire:loading.attr="disabled">
            <div class="flex items-center justify-center">
                <!-- Spinner -->
                <span wire:loading class="mr-2" wire:target="CreateReservation">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                        </path>
                    </svg>
                </span>
                <!-- Button Text -->
                <span wire:loading.remove wire:target="CreateReservation">
                    Create Reservation
                </span>
            </div>
        </x-button>

    </div>
</div>


{{-- add this after-> @foreach ($activities as $activity) --}}
{{-- @php
$isSelected = collect($selectedActivities)->contains('activity_id', $activity->id);
@endphp

@if ($isSelected)
@continue
@endif --}}