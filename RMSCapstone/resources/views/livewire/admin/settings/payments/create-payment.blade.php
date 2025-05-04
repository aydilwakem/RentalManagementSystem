<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">

    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">New Payment Method</h2>

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
            <div class="space-y-3">
                <label for="mode_of_payment_qr_image" class="block mb-2 text-sm font-medium text-gray-900">Upload QR
                    Image</label>
                <input accept="image/png, image/jpeg" type="file" wire:model="mode_of_payment_qr_image"
                    id="mode_of_payment_qr_image"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                <!-- Error Message -->
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Uploading Spinner -->
                <div wire:loading wire:target="mode_of_payment_qr_image" class="flex items-center justify-center px-5">
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
                            <img src="{{ $mode_of_payment_qr_image->temporaryUrl() }}" class="w-full h-48 object-contain rounded-lg shadow"
                                alt="Image Preview">
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
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                Add Payment Method
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

            <x-button class="ms-3 bg-green text-white" wire:click="savePaymentMethod" wire:loading.attr="disabled">
                {{ __('Create Payment Method') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
