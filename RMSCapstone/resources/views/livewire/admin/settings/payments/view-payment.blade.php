<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg mb-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Payment Method') }}
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

        <h2 class="mb-4 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center">
            {{ $paymentMethod->mode_of_payment_name }}
        </h2>

        <!-- QR Image -->
        <div class="mb-4">
            <img src="{{ asset('storage/' . $paymentMethod->mode_of_payment_qr_image) }}" alt="Method Image"
                class="w-full h-64 object-cover rounded-lg shadow-md">
        </div>

        <!-- Account Name -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Account Name:</h3>
            <p class=" text-gray-500">
                {{ $paymentMethod->account_name }}
            </p>
        </div>

        <!-- Account Number -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Account Details:</h3>
            <p class=" text-gray-500">
                {{ $paymentMethod->account_number }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-6 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-payment', ['paymentMethod' => $paymentMethod->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="deletePaymentMethod({{ $paymentMethod->id }})">
                Delete
            </x-button>

        </div>

    </div>
</div>
