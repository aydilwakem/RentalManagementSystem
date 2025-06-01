<div>
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
            <img class="w-full h-56 object-contain transition duration-300"
                src="{{ asset($paymentMethod->mode_of_payment_qr_image ? 'storage/' . $paymentMethod->mode_of_payment_qr_image : 'images/rms-default.png') }}"
                alt="{{ $paymentMethod->mode_of_payment_name }}" />
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
                wire:click="confirmDelete({{ $paymentMethod->id }})">
                Delete
            </x-button>

        </div>
        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Payment Method') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deletePaymentMethod({{ $paymentMethod->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Payment Method') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This payment method is currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>