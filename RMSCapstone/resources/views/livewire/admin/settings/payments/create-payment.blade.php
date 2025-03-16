<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-8 mt-8">
    <div class="mx-auto max-w-2xl lg:py-2s">
        <h2 class="mb-4 text-xl font-bold text-gray-900">New Payment Method</h2>

        {{-- Display Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form wire:submit.prevent="savePaymentMethod">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                {{-- Payment Method Name --}}
                <div class="sm:col-span-2">
                    <label for="mode_of_payment_name" class="block mb-2 text-sm font-medium text-gray-900">Payment
                        Method
                        Name</label>
                    <input type="text" wire:model="mode_of_payment_name" id="mode_of_payment_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type mode of payment" required>
                </div>

                {{-- Account Name --}}
                <div>
                    <label for="account_name" class="block mb-2 text-sm font-medium text-gray-900">Account
                        Name</label>
                    <input type="text" wire:model="account_name" id="account_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                {{-- Account Number --}}
                <div>
                    <label for="account_number" class="block mb-2 text-sm font-medium text-gray-900">Account
                        Number</label>
                    <input type="text" wire:model="account_number" id="account_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- QR Image Upload -->
                <div class="sm:col-span-2">
                    <label for="mode_of_payment_qr_image" class="block mb-2 text-sm font-medium text-gray-900">Upload QR
                        Image</label>
                    <input accept="image/png, image/jpeg" type="file" wire:model="mode_of_payment_qr_image"
                        id="mode_of_payment_qr_image"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    <!-- Error Message -->
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Loading Indicator (Shows when file is being uploaded) -->
                    <div wire:loading wire:target="image" class="mt-2 text-gray-600">
                        Uploading image...
                    </div>

                    <!-- Image Preview (Only if an image is selected and processed) -->
                    @if ($mode_of_payment_qr_image && method_exists($mode_of_payment_qr_image, 'temporaryUrl'))
                        <div class="mt-2">
                            <img src="{{ $mode_of_payment_qr_image->temporaryUrl() }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
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
        <x-button type="submit"
            wire:loading.attr="disabled" wire:target="image">
            Add Payment Method
        </x-button>
    </div>
    </form>
</div>
</div>
