<div>
    <!-- Form container -->
    <div class="mx-4 sm:mx-auto border rounded-xl p-8 dark:border-gray-600">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center dark:text-white">Edit Branding</h2>

        @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
        @endif

        <form wire:submit.prevent="">

            <!-- General Information -->
            <h3 class="font-bold text-lg text-green-700 dark:text-green-300">General Information</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Company
                        Logo</label>
                    <input type="file" wire:model="newImage" id="image" accept="image/png, image/jpeg" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('newImage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="newImage" class="mt-2 text-gray-600 dark:text-gray-200">Uploading
                        image...</div>
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
                    @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Name -->
                <div>
                    <label for="company_name"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Company Name</label>
                    <input type="company_name" wire:model="company_name" id="company_name" required
                        placeholder="Ex. ABC Rentals" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('company_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Email</label>
                    <input type="email" wire:model="email" id="email" required placeholder="Ex. company@example.com"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Contact
                        Number</label>
                    <input type="text" wire:model="contact_number" id="contact_number" placeholder="Ex. 0912 345 6789"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('contact_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Address</label>
                    <input type="text" wire:model="address" id="address" placeholder="Ex. 123 Main St, City, Country"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Social Media Links -->
            <h3 class="font-bold text-lg text-green-700 mt-8 dark:text-green-300">Social Media Links</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Facebook -->
                <div>
                    <label for="facebook"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Facebook</label>
                    <input type="text" wire:model="facebook" id="facebook"
                        placeholder="Ex. https://facebook.com/yourpage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('facebook')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instagram -->
                <div>
                    <label for="instagram"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Instagram</label>
                    <input type="text" wire:model="instagram" id="instagram"
                        placeholder="Ex. https://instagram.com/yourprofile" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                        dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('instagram')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- TODO: Add indicator for unsaved changes --}}
            <!-- Legal Information -->
            <!-- Info Icon with Tooltip -->
            <div class="relative group inline-flex items-center">
                <h3 class="font-bold text-lg text-green-700 mt-8 dark:text-green-300 mr-2">Legal Information</h3>
                <i class="fas fa-info-circle text-gray-500 text-sm cursor-pointer dark:text-gray-200 mt-8"></i>
                <div
                    class=" mb-2 w-max max-w-xs text-sm text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                    Refresh the page to enable editing.
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Terms and Conditions -->
                <div wire:ignore>
                    <label for="terms_and_conditions"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Terms and Conditions
                    </label>

                    <input type="hidden" id="terms_and_conditions" name="terms_and_conditions"
                        wire:model.lazy="terms_and_conditions">

                    <div id="editor-terms" class="bg-white dark:bg-gray-800 p-2 rounded shadow h-32 overflow-y-auto">
                        {!! $terms_and_conditions !!}
                    </div>
                </div>

                <!-- Privacy Policy -->
                <div wire:ignore>
                    <label for="privacy_policy" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Privacy Policy
                    </label>

                    <input type="hidden" id="privacy_policy" name="privacy_policy" wire:model.lazy="privacy_policy">

                    <div id="editor-privacy" class="bg-white dark:bg-gray-800 p-2 rounded shadow h-32 overflow-y-auto">
                        {!! $privacy_policy !!}
                    </div>
                </div>

                <!-- Refund Policy -->
                <div wire:ignore>
                    <label for="refund_policy"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Refund
                        Policy</label>

                    <input id="refund_policy" type="hidden" name="refund_policy" wire:model.lazy="refund_policy">

                    <div id="editor-refund" class="bg-white dark:bg-gray-800 p-2 rounded shadow h-32 overflow-y-auto">
                        {!! $refund_policy !!}
                    </div>
                </div>

                <!-- Rental Agreement -->
                <div wire:ignore>
                    <label for="rental_agreement"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Rental
                        Agreement</label>

                    <input id="rental_agreement" type="hidden" name="rental_agreement"
                        wire:model.lazy="rental_agreement">

                    <div id="editor-rental" class="bg-white dark:bg-gray-800 p-2 rounded shadow h-32 overflow-y-auto">
                        {!! $rental_agreement !!}
                    </div>
                </div>

            </div>

            <!-- Rental Settings -->
            <h3 class="font-bold text-lg text-green-700 mt-8 mb-2 dark:text-green-300">Rental Settings</h3>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Enable Deposit Option -->
                {{-- <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="enable_deposit" wire:model="enable_deposit_percentage" class="mr-2">
                        <span class="text-sm text-gray-900 dark:text-gray-200">
                            Enable Deposit Option for Bookings
                        </span>
                    </label>

                    @error('enable_deposit_percentage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Deposit Option for Bookings
                    </label>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 dark:text-gray-200">Disable</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="enable_deposit" wire:model="enable_deposit_percentage"
                                class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition">
                            </div>
                            <div
                                class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                        <span class="text-gray-700 dark:text-gray-200">Enable</span>
                    </div>
                    @error('enable_deposit_percentage')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>



                <!-- Room Payment Proof Expiration Hours -->
                <div>
                    <label for="room_payment_proof_expiration_hours"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Room Payment Proof Expiration (Hours)
                    </label>
                    <input type="number" min="1" wire:model="room_payment_proof_expiration_hours"
                        id="room_payment_proof_expiration_hours" placeholder="e.g., 24" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('room_payment_proof_expiration_hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Payment Proof Expiration Hours -->
                <div>
                    <label for="event_payment_proof_expiration_hours"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Event Payment Proof Expiration (Hours)
                    </label>
                    <input type="number" min="1" wire:model="event_payment_proof_expiration_hours"
                        id="event_payment_proof_expiration_hours" placeholder="e.g., 24" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('event_payment_proof_expiration_hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Day Tour Payment Proof Expiration Hours -->
                <div>
                    <label for="day_tour_payment_proof_expiration_hours"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Day Tour Payment Proof Expiration (Hours)
                    </label>
                    <input type="number" min="1" wire:model="day_tour_payment_proof_expiration_hours"
                        id="day_tour_payment_proof_expiration_hours" placeholder="e.g., 24" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('day_tour_payment_proof_expiration_hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deposit Percentage -->
                @if ($enable_deposit_percentage)
                <div>
                    <label for="deposit_percentage"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Deposit Percentage (%)
                    </label>
                    <input type="number" min="0" max="100" step="0.01" wire:model="deposit_percentage"
                        id="deposit_percentage" placeholder="e.g., 50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                    focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                </div>
                @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // TERMS
            const quillTerms = new Quill('#editor-terms', {
                theme: 'snow'
            });
            const inputTerms = document.getElementById('terms_and_conditions');
            if (inputTerms.value) {
                quillTerms.root.innerHTML = inputTerms.value;
            }
            quillTerms.on('text-change', function () {
                inputTerms.value = quillTerms.root.innerHTML;
                inputTerms.dispatchEvent(new Event('input'));
            });

            // PRIVACY
            const quillPrivacy = new Quill('#editor-privacy', {
                theme: 'snow'
            });
            const inputPrivacy = document.getElementById('privacy_policy');
            if (inputPrivacy.value) {
                quillPrivacy.root.innerHTML = inputPrivacy.value;
            }
            quillPrivacy.on('text-change', function () {
                inputPrivacy.value = quillPrivacy.root.innerHTML;
                inputPrivacy.dispatchEvent(new Event('input'));
            });

            // REFUND
            const quillRefund = new Quill('#editor-refund', {
                theme: 'snow'
            });
            const inputRefund = document.getElementById('refund_policy');
            if (inputRefund.value) {
                quillRefund.root.innerHTML = inputRefund.value;
            }
            quillRefund.on('text-change', function () {
                inputRefund.value = quillRefund.root.innerHTML;
                inputRefund.dispatchEvent(new Event('input'));
            });

            // RENTAL
            const quillRental = new Quill('#editor-rental', {
                theme: 'snow'
            });
            const inputRental = document.getElementById('rental_agreement');
            if (inputRental.value) {
                quillRental.root.innerHTML = inputRental.value;
            }
            quillRental.on('text-change', function () {
                inputRental.value = quillRental.root.innerHTML;
                inputRental.dispatchEvent(new Event('input'));
            });
        });
    </script>


</div>