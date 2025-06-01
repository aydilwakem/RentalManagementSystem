<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Payment Method') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Payment Method</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.payments') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    {{-- Payment Method Name --}}
                    <div class="sm:col-span-2">
                        <label for="mode_of_payment_name" class="block mb-2 text-sm font-medium text-gray-900">Payment
                            Method
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="mode_of_payment_name" id="mode_of_payment_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. Gcash E-Wallet" required>
                        @error('mode_of_payment_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Account Name --}}
                    <div>
                        <label for="account_name" class="block mb-2 text-sm font-medium text-gray-900">Account
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="account_name" id="account_name"
                            placeholder="Ex. Juan Dela Cruz"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                        @error('account_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Account Number --}}
                    <div>
                        <label for="account_number" class="block mb-2 text-sm font-medium text-gray-900">Account
                            Number <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="account_number" id="account_number"
                            placeholder="Ex. 09123456789"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                        @error('account_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- QR Image Upload -->
                    <div class="space-y-3">
                        <label for="mode_of_payment_qr_image"
                            class="block mb-2 text-sm font-medium text-gray-900">Upload QR
                            Image <span class="text-red-500">*</span></label>
                        <input accept="image/png, image/jpeg" type="file" wire:model="mode_of_payment_qr_image"
                            id="mode_of_payment_qr_image"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-green-600 focus:border-green-600 block w-full p-2.5">

                        <!-- Error Message -->
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Uploading Spinner -->
                        <div wire:loading wire:target="mode_of_payment_qr_image"
                            class="flex items-center justify-center px-5">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>

                        <!-- Image Previews -->
                        <div wire:loading.remove wire:target="mode_of_payment_qr_image">
                            @if ($mode_of_payment_qr_image && method_exists($mode_of_payment_qr_image, 'temporaryUrl'))
                                <div class="relative mb-4">
                                    <img src="{{ $mode_of_payment_qr_image->temporaryUrl() }}"
                                        class="w-full h-48 object-contain rounded-lg shadow" alt="Image Preview">
                                </div>
                            @else
                                <!-- No image placeholder-->
                                <div
                                    class="w-full h-48 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg text-gray-400">
                                    No image selected
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Button Wrapper -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                        wire:click="confirmCreate">
                        Create Payment Method
                    </x-button>
                </div>
            </form>
            <!-- Create Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmCreateItem">
                <x-slot name="title">
                    {{ __('Create Payment Method') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to add this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="savePaymentMethod"
                        wire:loading.attr="disabled">
                        {{ __('Create Payment Method') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
