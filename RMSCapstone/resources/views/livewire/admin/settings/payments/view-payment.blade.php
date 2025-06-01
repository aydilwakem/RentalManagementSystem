<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Payment Method') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Method Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">{{ $paymentMethod->name_number }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.payments') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 top-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Event Hall Details -->
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
                <x-ghost-button type="button" icon="fas fa-pen-to-square"
                    wire:navigate href="{{ route('admin.edit-payment', ['paymentMethod' => $paymentMethod->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash"
                    wire:click="confirmDelete({{ $paymentMethod->id }})">
                    Delete
                </x-danger-button>

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

        </div>
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
