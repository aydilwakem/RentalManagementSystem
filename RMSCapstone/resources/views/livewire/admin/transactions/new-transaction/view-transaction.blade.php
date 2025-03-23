<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View transactions') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto max-w-2xl lg:py-2 flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- transactions ID -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">
            {{ $transactions->id }}
        </h2>

        <!-- Guest Information -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Guest Information</h3>
            <p class="text-gray-600"><strong>Name:</strong> {{ $transactions->first_name }}
                {{ $transactions->middle_name }} {{ $transactions->last_name }} {{ $transactions->suffix }}
            </p>
            <p class="text-gray-600"><strong>Email:</strong> {{ $transactions->email }}</p>
            <p class="text-gray-600"><strong>Contact Number:</strong> {{ $transactions->contact_number }}</p>
        </div>

        <!-- Address -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Address</h3>
            <p class="text-gray-600">{{ $transactions->house_number }} {{ $transactions->street }},
                {{ $transactions->barangay }}, {{ $transactions->city_municipality }}, {{ $transactions->province }},
                {{ $transactions->region }}, {{ $transactions->postal_code }}, {{ $transactions->country }}
            </p>
        </div>

        <!-- Transaction Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Reservation Details</h3>
            <p class="text-gray-600"><strong>Room:</strong> {{ $transactions->room->name ?? 'No Room Assigned' }}</p>
            <p class="text-gray-600"><strong>Activity:</strong>
                {{ $transactions->activity->name ?? 'No Activity' }}</p>
            <ul class="list-disc pl-5 text-gray-600">

                <li><strong>Check-in Date:</strong>
                    {{ \Carbon\Carbon::parse($transactions->check_in_date)->format('Y-m-d') }}</li>
                <li><strong>Check-in Time:</strong>
                    {{ \Carbon\Carbon::parse($transactions->check_in_time)->format('h:i A') }}</li>
                <li><strong>Check-out Date:</strong>
                    {{ \Carbon\Carbon::parse($transactions->check_out_date)->format('Y-m-d') }}</li>
                <li><strong>Check-out Time:</strong>
                    {{ \Carbon\Carbon::parse($transactions->check_out_time)->format('h:i A') }}</li>

                <li><strong>Total Adults:</strong> {{ $transactions->total_adults }}</li>
                <li><strong>Total Kids:</strong> {{ $transactions->total_kids }}</li>
                <li><strong>Total Pax:</strong> {{ $transactions->pax }}</li>

                <li><strong>Total Males:</strong> {{ $transactions->total_males }}</li>
                <li><strong>Total Females:</strong> {{ $transactions->total_females }}</li>
                <li><strong>Total Infants:</strong> {{ $transactions->total_infants }}</li>
                <li><strong>Total People:</strong> {{ $transactions->total_people }}</li>
                <li><strong>Pets:</strong> {{ $transactions->pets}}</li>
            </ul>
        </div>

        <!-- Payment Details -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
            <p class="text-gray-600"><strong>Payment Method:</strong>
                {{ $transactions->paymentMethod->name ?? 'Not Provided' }}</p>
            <p class="text-gray-600"><strong>Total Amount:</strong>
                ₱{{ number_format($transactions->total_amount, 2) }}
            </p>
            <p class="text-gray-600"><strong>Payment Reference Number:</strong>
                {{ $transactions->payment_reference_number ?? 'N/A' }}</p>
            @if($transactions->payment_screenshot)
                <p class="text-gray-600"><strong>Payment Screenshot:</strong></p>
                <img src="{{ asset('storage/' . $transactions->payment_screenshot) }}" alt="Payment Proof"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $transactions->id }})">
                Delete
            </x-button>

        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete transactions') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this transactions?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deletetransactions({{ $transactions->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete transactions') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>