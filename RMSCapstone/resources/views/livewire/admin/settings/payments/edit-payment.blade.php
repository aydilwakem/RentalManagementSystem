<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Payment Method') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Payment Methods', 'url' => route('admin.payments')],
            [
                'label' => 'View Payment Method',
                'url' => route('admin.view-payment', ['paymentMethod' => $paymentMethod->id]),
            ],
            [
                'label' => 'Edit Payment Method',
                'url' => route('admin.edit-payment', ['paymentMethod' => $paymentMethod->id]),
            ],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div>
        <div
            class="mx-auto max-w-3xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Payment Method
                    Details</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    {{-- Payment Method Name --}}
                    <div class="sm:col-span-2">
                        <label for="mode_of_payment_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Payment
                            Method
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="mode_of_payment_name" id="mode_of_payment_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Gcash E-Wallet" required>
                        @error('mode_of_payment_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Account Name --}}
                    <div>
                        <label for="account_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Account
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="account_name" id="account_name"
                            placeholder="Ex. Juan Dela Cruz"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('account_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Account Number --}}
                    <div>
                        <label for="account_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Account
                            Number <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="account_number" id="account_number"
                            placeholder="Ex. 09123456789"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('account_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <!-- QR Upload -->
                    <div class="mb-4 col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            QR Code Image
                        </label>

                        <div class="flex gap-4 items-center">

                            {{-- Show preview: if new uploaded image, show it; otherwise show existing --}}
                            <div class="relative">
                                @if ($new_mode_of_payment_qr_image)
                                    <img src="{{ $new_mode_of_payment_qr_image->temporaryUrl() }}"
                                        class="w-52 h-40 object-cover rounded-md shadow-sm border" alt="QR Preview">
                                @elseif ($mode_of_payment_qr_image)
                                    <img src="{{ asset('storage/' . $mode_of_payment_qr_image) }}"
                                        class="w-52 h-40 object-cover rounded-md shadow-sm border" alt="Current QR">
                                @else
                                    <img src="{{ asset('images/placeholder.png') }}"
                                        class="w-52 h-40 object-cover rounded-md shadow-sm border" alt="Placeholder">
                                @endif
                            </div>

                            {{-- Upload box always visible --}}
                            <label for="qrUploadEdit" class="cursor-pointer shrink-0">
                                <div
                                    class="w-52 h-40 border-2 border-dashed border-gray-400 rounded-md flex flex-col items-center justify-center text-gray-400 hover:bg-gray-50 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="text-xs">Replace QR Code</span>
                                </div>
                            </label>

                            <!-- Hidden file input -->
                            <input id="qrUploadEdit" type="file" wire:model="new_mode_of_payment_qr_image"
                                accept="image/png, image/jpeg" class="hidden">
                        </div>

                        @error('new_mode_of_payment_qr_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Upload Loading Spinner --}}
                        <div wire:loading wire:target="new_mode_of_payment_qr_image" class="flex items-center mt-2">
                            <svg class="animate-spin h-5 w-5 mr-2 text-green-700" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                            <span>Uploading...</span>
                        </div>
                    </div>


                </div>

                <!-- Button Wrapper -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>

                    <x-button wire:click="confirmEdit">
                        Save Changes
                    </x-button>

                </div>
            </form>
        </div>

        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Payment Method') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes to this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button wire:click="updatePaymentMethod" wire:loading.attr="disabled">
                    Save Changes
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Delete Image Confirmation Modal -->
        <x-dialog-modal wire:model="confirmDeleteImage" type="danger">
            <x-slot name="title">
                {{ __('Delete Image') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this image?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmDeleteImage', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button wire:click="removeStoredImage" wire:loading.attr="disabled">
                    {{ __('Delete') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>
