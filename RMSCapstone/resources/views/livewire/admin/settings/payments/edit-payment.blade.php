<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Payment Method') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Payment Methods', 'url' => route('admin.payments')],
            ['label' => 'View Payment Method', 'url' => route('admin.view-payment', ['paymentMethod' => $paymentMethod->id])],
            ['label' => 'Edit Payment Method', 'url' => route('admin.edit-payment', ['paymentMethod' => $paymentMethod->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div>
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Payment Method Details</h2>

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
                        <label for="mode_of_payment_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Payment
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
                        <label for="account_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Account
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
                        <label for="account_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Account
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
                    <div class="sm:col-span-2">
                        <label for="mode_of_payment_qr_image"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Upload
                            New Image <span class="text-red-500">*</span></label>
                        <input type="file" wire:model="new_mode_of_payment_qr_image" id="mode_of_payment_qr_image"
                            accept="image/png, image/jpeg"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">

                        @error('new_mode_of_payment_qr_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="new_mode_of_payment_qr_image" class="mt-2 text-gray-600 dark:text-gray-200">
                            Uploading
                            image...</div>

                        <!-- Image Preview -->
                        <div class="mt-2">
                            @if ($new_mode_of_payment_qr_image)
                                <!-- Show new uploaded image -->
                                <img src="{{ $new_mode_of_payment_qr_image->temporaryUrl() }}"
                                    class="w-32 h-32 object-cover rounded-lg shadow">
                            @elseif ($paymentMethod->mode_of_payment_qr_image)
                                <!-- Show existing image from storage -->
                                <img src="{{ asset('storage/' . $paymentMethod->mode_of_payment_qr_image) }}"
                                    class="w-32 h-32 object-cover rounded-lg shadow">
                            @else
                                <!-- Show default image if no image exists -->
                                <img src="{{ asset('images/rms-default.png') }}"
                                    class="w-32 h-32 object-cover rounded-lg shadow">
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
                        wire:click="confirmEdit({{ $paymentMethod->id }})">
                        Save Changes
                    </x-button>
                </div>
            </form>
        </div>
        <!-- Edit Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmEditItem">
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

                <x-button class="ms-3 bg-green text-white" wire:click="updatePaymentMethod({{ $paymentMethod->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Save Changes') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
