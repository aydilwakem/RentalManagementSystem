<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg mb-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment Method') }}
        </h2>
    </x-slot>
    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">

        <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Payment Method</h2>


        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                {{-- Payment Method Name --}}
                <div class="sm:col-span-2">
                    <label for="mode_of_payment_name" class="block mb-2 text-sm font-medium text-gray-900">Payment
                        Method
                        Name</label>
                    <input type="text" wire:model="mode_of_payment_name" id="mode_of_payment_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type mode of payment" required>
                    @error('mode_of_payment_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Account Name --}}
                <div>
                    <label for="account_name" class="block mb-2 text-sm font-medium text-gray-900">Account
                        Name</label>
                    <input type="text" wire:model="account_name" id="account_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('account_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Account Number --}}
                <div>
                    <label for="account_number" class="block mb-2 text-sm font-medium text-gray-900">Account
                        Number</label>
                    <input type="text" wire:model="account_number" id="account_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('account_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- QR Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                    <input accept="image/png, image/jpeg" type="file" wire:model="new_mode_of_payment_qr_image"
                        id="image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    <!-- Error Message -->
                    @error('new_mode_of_payment_qr_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Loading Indicator (Shows when file is being uploaded) -->
                    <div wire:loading wire:target="new_mode_of_payment_qr_image" class="mt-2 text-blue-600">
                        Uploading image...
                    </div>

                    <!-- Image Preview (Shows New Image if Selected, Otherwise Shows Current Image) -->
                    <div class="mt-2">
                        @if ($new_mode_of_payment_qr_image)
                        <img src="{{ $new_mode_of_payment_qr_image->temporaryUrl() }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($mode_of_payment_qr_image)
                        <img src="{{ asset('storage/' . $mode_of_payment_qr_image) }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>

                <!-- Button Wrapper -->
                <div class="sm:col-span-2 flex justify-between items-center mt-6 space-x-4 mb-4">
                    <x-button onclick="history.back()" type="button"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>

                    <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                        wire:click="confirmEdit({{ $paymentMethod->id }})">
                        Save Changes
                    </x-button>
                </div>
            </div>
        </form>
        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
            <x-slot name="title">
                {{ __('Edit Payment Method') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to save changes on this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="updatePaymentMethod({{ $paymentMethod->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Edit Payment Method') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>