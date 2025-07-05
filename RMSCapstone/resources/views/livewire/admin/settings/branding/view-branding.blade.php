<div>
    <!-- Form container -->
    <div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Branding</h2>

        @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
            {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
        @endif

        <form wire:submit.prevent="">

            <!-- General Information -->
            <h3 class="font-semibold text-lg text-gray-900 mb-2">General Information</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block mb-2 text-sm font-medium text-gray-900">Company Logo</label>
                    <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('newImage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="newImage" class="mt-2 text-gray-600">Uploading image...</div>
                    <div class="mt-2">
                        @if ($newImage)
                        <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($settings && $settings->logo)
                        <img src="{{ asset('storage/' . $settings->logo) }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                        @else
                        <img src="{{ asset('images/rms-default.png') }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>

                <!-- Company Name -->
                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                    <input type="company_name" wire:model="company_name" id="company_name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" wire:model="email" id="email" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                        Number</label>
                    <input type="text" wire:model="contact_number" id="contact_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input type="text" wire:model="address" id="address"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>
            </div>

            <!-- Social Media Links -->
            <h3 class="font-semibold text-lg text-gray-900 mt-8 mb-2">Social Media Links</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Facebook -->
                <div>
                    <label for="facebook" class="block mb-2 text-sm font-medium text-gray-900">Facebook</label>
                    <input type="text" wire:model="facebook" id="facebook"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Instagram -->
                <div>
                    <label for="instagram" class="block mb-2 text-sm font-medium text-gray-900">Instagram</label>
                    <input type="text" wire:model="instagram" id="instagram"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>
            </div>

            <!-- Legal Information -->
            <h3 class="font-semibold text-lg text-gray-900 mt-8 mb-2">Legal Information</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Terms and Conditions -->
                <div wire:ignore>
                    <label for="terms_and_conditions" class="block mb-2 text-sm font-medium text-gray-900">Terms and
                        Conditions</label>
                    <input id="terms_and_conditions" type="hidden" name="terms_and_conditions"
                        wire:model.lazy="terms_and_conditions">
                    <trix-editor input="terms_and_conditions"></trix-editor>
                </div>


                <!-- Privacy Policy -->
                <div wire:ignore>
                    <label for="privacy_policy" class="block mb-2 text-sm font-medium text-gray-900">Privacy
                        Policy</label>
                    <input id="privacy_policy" type="hidden" name="privacy_policy" wire:model.lazy="privacy_policy">
                    <trix-editor input="privacy_policy"></trix-editor>
                </div>

                <!-- Refund Policy -->
                <div wire:ignore>
                    <label for="refund_policy" class="block mb-2 text-sm font-medium text-gray-900">Refund
                        Policy</label>
                    <input id="refund_policy" type="hidden" name="refund_policy" wire:model.lazy="refund_policy">
                    <trix-editor input="refund_policy"></trix-editor>
                </div>

                <!-- Rental Agreement -->
                <div wire:ignore>
                    <label for="rental_agreement" class="block mb-2 text-sm font-medium text-gray-900">Rental
                        Agreement</label>
                    <input id="rental_agreement" type="hidden" name="rental_agreement"
                        wire:model.lazy="rental_agreement">
                    <trix-editor input="rental_agreement"></trix-editor>
                </div>

            </div>

            <!-- Rental Settings -->
            <h3 class="font-semibold text-lg text-gray-900 mt-8 mb-2">Rental Settings</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Enable Deposit Option -->
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="enable_deposit" wire:model.live="enable_deposit_percentage"
                            class="mr-2">
                        <span class="text-sm text-gray-900">Enable Deposit Option for Bookings</span>
                    </label>

                    @error('enable_deposit_percentage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deposit Percentage -->
                @if($enable_deposit_percentage)
                <div>
                    <label for="deposit_percentage" class="block mb-2 text-sm font-medium text-gray-900">
                        Deposit Percentage (%)
                    </label>
                    <input type="number" min="0" max="100" step="0.01" wire:model="deposit_percentage"
                        id="deposit_percentage" placeholder="e.g., 50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
            focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>
                @endif

                <!-- Payment Proof Expiration Hours -->
                <div>
                    <label for="payment_proof_expiration_hours" class="block mb-2 text-sm font-medium text-gray-900">
                        Payment Proof Expiration (Hours)
                    </label>
                    <input type="number" min="1" wire:model="payment_proof_expiration_hours"
                        id="payment_proof_expiration_hours" placeholder="e.g., 24" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                   focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>
            </div>


            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:loading.attr="disabled" wire:target="newImage"
                    wire:click="updateBranding()">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit Branding') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save your changes?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateBranding()" wire:loading.attr="disabled">
                {{ __('Save Changes') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>