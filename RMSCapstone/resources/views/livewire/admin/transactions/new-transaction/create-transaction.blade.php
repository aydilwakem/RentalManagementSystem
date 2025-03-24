<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-8 mt-8">


    <h2 class="mb-6 text-xl font-bold text-gray-900 text-center">Add New Reservation</h2>

    <form wire:submit.prevent="saveTransaction">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!----------------- GUEST DETAILS ------------------------------------------------------------>
            <!-- First Name -->
            <div>
                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                <input type="text" wire:model="first_name" id="first_name" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter first name">
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
                <input type="text" wire:model="last_name" id="last_name" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter last name">
                @error('last_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Suffix -->
            <div>
                <label for="suffix" class="block mb-2 text-sm font-medium text-gray-900">Suffix (e.g., Jr., Sr.,
                    III)</label>
                <input type="text" wire:model="suffix" id="suffix"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter suffix (if applicable)">
                @error('suffix')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!----------------- CONTACT DETAILS ------------------------------------------------------------>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" wire:model="email" id="email" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter email">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                    Number</label>
                <input type="tel" wire:model="contact_number" id="contact_number" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter contact number">
                @error('contact_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!----------------- ADDRESS ------------------------------------------------------------>

            <!-- House Number -->
            <div class="sm:col-span-2">
                <label for="house_number" class="block mb-2 text-sm font-medium text-gray-900">House
                    Number</label>
                <input type="text" wire:model="house_number" id="house_number" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter house number">
                @error('house_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Street -->
            <div>
                <label for="street" class="block mb-2 text-sm font-medium text-gray-900">Street</label>
                <input type="text" wire:model="street" id="street" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter street name">
                @error('street')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Barangay -->
            <div>
                <label for="barangay" class="block mb-2 text-sm font-medium text-gray-900">Barangay</label>
                <input type="text" wire:model="barangay" id="barangay" required
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
                <input type="text" wire:model="city_municipality" id="city_municipality" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter city or municipality">
                @error('city_municipality')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Province -->
            <div>
                <label for="province" class="block mb-2 text-sm font-medium text-gray-900">Province</label>
                <input type="text" wire:model="province" id="province" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter province">
                @error('province')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Region -->
            <div>
                <label for="region" class="block mb-2 text-sm font-medium text-gray-900">Region</label>
                <input type="text" wire:model="region" id="region" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter region">
                @error('region')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Postal Code -->
            <div>
                <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Postal
                    Code</label>
                <input type="text" wire:model="postal_code" id="postal_code" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter postal code">
                @error('postal_code')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country</label>
                <input type="text" wire:model="country" id="country" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter country">
                @error('country')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!----------------- RESERVATION DETAILS ------------------------------------------------------------>

            <!-- Room Selection -->
            <div class="sm:col-span-2">
                <label for="room_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Room
                </label>
                <select wire:model.defer="room_id" id="room_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    <option value="">-- Select Room --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
                @error('room_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Activities Selection -->
            <div class="sm:col-span-2">
                <label for="activity_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Activities
                </label>
                <select wire:model.defer="activity_id" id="activity_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    <option value="">-- Select Activity --</option>
                    @foreach($activities as $activity)
                        <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                    @endforeach
                </select>
                @error('activity_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Adults -->
            <div>
                <label for="total_adults" class="block mb-2 text-sm font-medium text-gray-900">Total Adults</label>
                <input type="number" wire:model="total_adults" id="total_adults" required min="0"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('total_adults')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Kids -->
            <div>
                <label for="total_kids" class="block mb-2 text-sm font-medium text-gray-900">Total Kids</label>
                <input type="number" wire:model="total_kids" id="total_kids" required min="0"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('total_kids')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Pax -->
            <div>
                <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">Pax</label>
                <input type="number" wire:model="pax" id="pax" required min="1"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('pax')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Amount -->
            <div>
                <label for="total_amount" class="block mb-2 text-sm font-medium text-gray-900">Total Amount</label>
                <input type="number" wire:model="total_amount" id="total_amount" required min="0" step="0.01"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('total_amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>


            <!-- Check-in Date -->
            <div>
                <label for="check_in_date" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                    Date</label>
                <input type="date" wire:model="check_in_date" id="check_in_date" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('check_in_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Check-in Time -->
            <div>
                <label for="check_in_time" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                    Time</label>
                <input type="time" wire:model="check_in_time" id="check_in_time" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('check_in_time')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Check-out Date -->
            <div>
                <label for="check_out_date" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                    Date</label>
                <input type="date" wire:model="check_out_date" id="check_out_date" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('check_out_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Check-out Time -->
            <div>
                <label for="check_out_time" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                    Time</label>
                <input type="time" wire:model="check_out_time" id="check_out_time" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('check_out_time')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Other Details -->


            <!-- Pets -->
            <div>
                <label for="pets" class="block mb-2 text-sm font-medium text-gray-900">Pets</label>
                <input type="number" wire:model="pets" id="pets" required min="0"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('pets')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!----------------- PAYMENT INFORMATION ------------------------------------------------------------>

            <!-- Payment Method-->
            <div class="sm:col-span-2">
                <label for="payment_method_id" class="block mb-2 text-sm font-medium text-gray-900">
                    Payment Method
                </label>
                <select wire:model.defer="payment_method_id" id="payment_method_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    <option value="">-- Select Payment Method --</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->mode_of_payment_name }}</option>
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
                <input type="text" wire:model.defer="payment_reference_number" id="payment_reference_number" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter payment reference number">
                @error('payment_reference_number')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Payment Screenshot -->
            <div class="sm:col-span-2">
                <label for="payment_screenshot" class="block mb-2 text-sm font-medium text-gray-900">Upload
                    Payment Screenshot</label>
                <input type="file" wire:model="payment_screenshot" id="image" accept="image/png, image/jpeg"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">

                @error('payment_screenshot')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div wire:loading wire:target="payment_screenshot" class="mt-2 text-gray-600">
                    Uploading image...
                </div>

                @if ($payment_screenshot && method_exists($payment_screenshot, 'temporaryUrl'))
                    <div class="mt-2">
                        <img src="{{ $payment_screenshot->temporaryUrl() }}"
                            class="w-32 h-32 object-cover rounded-lg shadow">
                    </div>
                @endif
            </div>

            <!----------------- END OF PAYMENT INFORMATION ------------------------------------------------------------>

            <!-- Terms Agreement -->
            <div class="sm:col-span-2 flex items-center">
                <input type="checkbox" wire:model="terms" id="terms"
                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-600">
                <label for="terms" class="ml-2 text-sm font-medium text-gray-900">
                    I agree to the <a href="/terms-and-conditions" class="text-primary-600 underline">Terms and
                        Conditions</a>
                </label>
                @error('terms')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Residents Section -->
        <div>
            @foreach($residents as $index => $resident)
                <div>
                    <h4 class="text-lg font-semibold">Guest {{ $index + 1 }}</h4> <!-- Adding Guest 1, Guest 2, etc. -->

                    <label for="residents.{{ $index }}.name" class="block mb-2 text-sm font-medium text-gray-900">Resident
                        Name</label>
                    <input type="text" wire:model="residents.{{ $index }}.name" id="residents.{{ $index }}.name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error("residents.$index.name") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="residents.{{ $index }}.residency_status"
                        class="block mb-2 text-sm font-medium text-gray-900">Residency Status</label>
                    <select wire:model="residents.{{ $index }}.residency_status"
                        id="residents.{{ $index }}.residency_status" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Residency Status</option>
                        <option value="Local">Local</option>
                        <option value="Foreigner">Foreigner</option>
                    </select>
                    @error("residents.$index.residency_status") <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="residents.{{ $index }}.origin" class="block mb-2 text-sm font-medium text-gray-900">
                        Origin
                    </label>
                    <input type="text" wire:model="residents.{{ $index }}.origin" id="residents.{{ $index }}.origin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                                               focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error("residents.$index.origin")
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <div>
                    <label for="residents.{{ $index }}.demographic"
                        class="block mb-2 text-sm font-medium text-gray-900">Demographic</label>
                    <select wire:model="residents.{{ $index }}.demographic" id="residents.{{ $index }}.demographic" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Demographic</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                        <option value="infant">Infant</option>
                    </select>
                    @error("residents.$index.demographic") <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach
        </div>



        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button wire:loading.attr="disabled" wire:target="payment_screenshot" wire:click="confirmCreate">
                Add Reservation
            </x-button>
        </div>
    </form>
</div>