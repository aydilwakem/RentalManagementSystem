<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Transaction') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border mb-4 mt-4 bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Transaction</h2>

        <!----------------------------- Form ------------------------------------------>
        <form wire:submit.prevent="">
            <!----------------------------- Start of Form Card ------------------------------------------>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!----------------------------- Reservation Holder Details ------------------------------------------------->

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                    <input type="text" wire:model="first_name" id="first_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter first name" required>
                    @error('first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Middle Name -->
                <div>
                    <label for="middle_name" class="block mb-2 text-sm font-medium text-gray-900">Middle Name</label>
                    <input type="text" wire:model="middle_name" id="middle_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter middle name">
                    @error('middle_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                    <input type="text" wire:model="last_name" id="last_name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter last name" required>
                    @error('last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Suffix -->
                <div>
                    <label for="suffix" class="block mb-2 text-sm font-medium text-gray-900">Suffix</label>
                    <input type="text" wire:model="suffix" id="suffix"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter suffix (e.g., Jr., Sr., III)">
                    @error('suffix')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!----------------------------- Contact Information ------------------------------->

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input type="email" wire:model="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter email" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                        Number</label>
                    <input type="text" wire:model="contact_number" id="contact_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter contact number" required>
                    @error('contact_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!----------------------------- Address ------------------------------------------->

                <!-- House Number -->
                <div>
                    <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House Number</label>
                    <input type="text" wire:model="house_number" id="house_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter house number">
                    @error('house_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Street -->
                <div>
                    <label for="street" class="block mb-2 text-sm font-medium text-gray-900">Street</label>
                    <input type="text" wire:model="street" id="street"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter street">
                    @error('street')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Barangay -->
                <div>
                    <label for="barangay" class="block mb-2 text-sm font-medium text-gray-900">Barangay</label>
                    <input type="text" wire:model="barangay" id="barangay"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter barangay">
                    @error('barangay')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- City / Municipality -->
                <div>
                    <label for="city_municipality" class="block mb-2 text-sm font-medium text-gray-900">City /
                        Municipality</label>
                    <input type="text" wire:model="city_municipality" id="city_municipality"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter city/municipality">
                    @error('city_municipality')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block mb-2 text-sm font-medium text-gray-900">Province</label>
                    <input type="text" wire:model="province" id="province"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter province">
                    @error('province')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Region -->
                <div>
                    <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                    <input type="text" wire:model="region" id="region"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter region">
                    @error('region')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Postal Code -->
                <div>
                    <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal Code</label>
                    <input type="text" wire:model="postal_code" id="postal_code"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter postal code">
                    @error('postal_code')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country</label>
                    <input type="text" wire:model="country" id="country"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter country">
                    @error('country')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>



                <!----------------------------- Reservation Details ------------------------------------------------->

                <!-- Room Selection -->
                <div>
                    <label for="room_id" class="block mb-2 text-sm font-medium text-gray-900">Room</label>
                    <select wire:model="room_id" id="room_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select a Room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Activity Selection -->
                <div>
                    <label for="activity_id" class="block mb-2 text-sm font-medium text-gray-900">Activity</label>
                    <select wire:model="activity_id" id="activity_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select an Activity</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                        @endforeach
                    </select>
                    @error('activity_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Check-in date -->
                <div>
                    <label for="check_in_date" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                        Date</label>
                    <input type="date" wire:model="check_in_date" id="check_in_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('check_in_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Check-out date -->
                <div>
                    <label for="check_out_date" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                        Date</label>
                    <input type="date" wire:model="check_out_date" id="check_out_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('check_out_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Check-in Time -->
                <div>
                    <label for="check_in_time" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                        Time</label>
                    <input type="time" wire:model="check_in_time" id="check_in_time"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('check_in_time')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Check-out Time -->
                <div>
                    <label for="check_out_time" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                        Time</label>
                    <input type="time" wire:model="check_out_time" id="check_out_time"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('check_out_time')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Total Adults -->
                <div>
                    <label for="total_adults" class="block mb-2 text-sm font-medium text-gray-900">Total Adults</label>
                    <input type="number" wire:model="total_adults" id="total_adults"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        min="1">
                    @error('total_adults')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Total Kids -->
                <div>
                    <label for="total_kids" class="block mb-2 text-sm font-medium text-gray-900">Total Kids</label>
                    <input type="number" wire:model="total_kids" id="total_kids"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        min="0">
                    @error('total_kids')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pax -->
                <div>
                    <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">Total Pax</label>
                    <input type="number" wire:model="pax" id="pax"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        min="1">
                    @error('pax')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Total Amount -->
                <div>
                    <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total Amount</label>
                    <input type="text" wire:model="total_amount" id="total_amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter total amount">
                    @error('total_amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pets Allowed -->
                <div>
                    <label for="pets" class="block mb-2 text-sm font-medium text-gray-900">Pets</label>
                    <input type="text" wire:model="pets" id="pets"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter pet details">
                    @error('pets')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!----------------------------- Payment Details ------------------------------------------------->

                <!-- Payment Method -->
                <div>
                    <label for="payment_method_id" class="block mb-2 text-sm font-medium text-gray-900">Payment
                        Method</label>
                    <select wire:model="payment_method_id" id="payment_method_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Payment Method</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->mode_of_payment_name}}</option>
                        @endforeach
                    </select>
                    @error('payment_method_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Reference Number -->
                <div>
                    <label for="payment_reference_number" class="block mb-2 text-sm font-medium text-gray-900">Payment
                        Reference Number</label>
                    <input type="text" wire:model="payment_reference_number" id="payment_reference_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter payment reference number">
                    @error('payment_reference_number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Payment Screenshot Upload -->
                <div class="sm:col-span-2">
                    <label for="payment_screenshot" class="block mb-2 text-sm font-medium text-gray-900">Upload New
                        Image
                        (Optional)</label>
                    <input type="file" wire:model="newImage" id="payment_screenshot" accept="image/png, image/jpeg"
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
                        @elseif ($transaction->payment_screenshot)
                            <!-- Show existing image from storage -->
                            <img src="{{ asset('storage/' . $transaction->payment_screenshot) }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @else
                            <!-- Show default image if no image exists -->
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="w-32 h-32 object-cover rounded-lg shadow">
                        @endif
                    </div>
                </div>

                <!-- Terms and Conditions Checkbox -->
                <div class="flex items-center mt-2">
                    <input type="checkbox" wire:model="terms" id="terms"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="terms" class="ml-2 text-sm font-medium text-gray-900">I agree to the Terms and
                        Conditions</label>
                    @error('terms')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <!----------------------------- End of Form Card ------------------------------------------>

            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:click="confirmEdit({{ $transaction->id }})" wire:loading.attr="disabled">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit Transaction') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateTransaction({{ $transaction->id }})"
                wire:loading.attr="disabled">
                {{ __('Edit Transaction') }}
            </x-button>

        </x-slot>
    </x-dialog-modal>

</div>