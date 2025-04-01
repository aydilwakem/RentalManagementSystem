<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg" x-data="{ showConfirm: false }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit brand details') }}
        </h2>
    </x-slot>
    <!-- Form container -->
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Branding</h2>
        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Logo Upload -->
                <div class="sm:col-span-2">
                    <label for="logo" class="block mb-2 text-sm font-medium text-gray-900">Company Logo</label>
                    <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                    @error('newImage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="newImage" class="mt-2 text-gray-600">Uploading image...</div>

                    <!-- Image Preview -->
                    <div class="mt-2">
                        @if ($newImage)
                            <!-- Show new uploaded image -->
                            <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg shadow">
                        @elseif ($settings && $settings->logo) <!-- Use $settings->logo instead of $settings->image -->
                            <!-- Show existing image from storage -->
                            <img src="{{ asset('storage/' . $settings->logo) }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @else
                            <!-- Show default image if no image exists -->
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif

                    </div>
                </div>


                <!-- Company Name -->
                <div class="sm:col-span-2">
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                    <input type="company_name" wire:model="company_name" id="company_name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Email -->
                <div class="sm:col-span-2">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" wire:model="email" id="email" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Contact Number -->
                <div class="sm:col-span-2">
                    <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                        Number</label>
                    <input type="text" wire:model="contact_number" id="contact_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input type="text" wire:model="address" id="address"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Facebook -->
                <div class="sm:col-span-2">
                    <label for="facebook" class="block mb-2 text-sm font-medium text-gray-900">Facebook</label>
                    <input type="text" wire:model="facebook" id="facebook"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Instagram -->
                <div class="sm:col-span-2">
                    <label for="instagram" class="block mb-2 text-sm font-medium text-gray-900">Instagram</label>
                    <input type="text" wire:model="instagram" id="instagram"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Terms and Conditions -->
                <div class="sm:col-span-2">
                    <label for="terms_and_conditions" class="block mb-2 text-sm font-medium text-gray-900">Terms and
                        Conditions</label>
                    <textarea wire:model="terms_and_conditions" id="terms_and_conditions"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
                </div>

                <!-- Privacy Policy -->
                <div class="sm:col-span-2">
                    <label for="privacy_policy" class="block mb-2 text-sm font-medium text-gray-900">Privacy
                        Policy</label>
                    <textarea wire:model="privacy_policy" id="privacy_policy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
                </div>

                <!-- Refund Policy -->
                <div class="sm:col-span-2">
                    <label for="refund_policy" class="block mb-2 text-sm font-medium text-gray-900">Refund
                        Policy</label>
                    <textarea wire:model="refund_policy" id="refund_policy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
                </div>

                <!-- Rental Agreement -->
                <div class="sm:col-span-2">
                    <label for="rental_agreement" class="block mb-2 text-sm font-medium text-gray-900">Rental
                        Agreement</label>
                    <textarea wire:model="rental_agreement" id="rental_agreement"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
                </div>

                <!-- Custom CSS -->
                <div class="sm:col-span-2">
                    <label for="custom_css" class="block mb-2 text-sm font-medium text-gray-900">Custom CSS</label>
                    <textarea wire:model="custom_css" id="custom_css"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
                </div>

                <!-- Custom JS -->
                <div class="sm:col-span-2">
                    <label for="custom_js" class="block mb-2 text-sm font-medium text-gray-900">Custom JS</label>
                    <textarea wire:model="custom_js" id="custom_js"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"></textarea>
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
