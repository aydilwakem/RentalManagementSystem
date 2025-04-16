<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6 shadow-md">
        <div class="mx-auto max-w-2xl lg:py-2">
            <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add New Reservation</h2>

            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Guest Name -->
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                        <input type="text" wire:model="first_name" id="first_name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter first name">
                        @error('first_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block mb-2 text-sm font-medium text-gray-900">Middle
                            Name</label>
                        <input type="text" wire:model="middle_name" id="middle_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter middle name">
                        @error('middle_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                        <input type="text" wire:model="last_name" id="last_name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter last name">
                        @error('last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="suffix" class="block mb-2 text-sm font-medium text-gray-900">Suffix</label>
                        <input type="text" wire:model="suffix" id="suffix"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter suffix (if any)">
                        @error('suffix')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Information -->

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" wire:model="email" id="email" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter email">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900">Contact
                            Number</label>
                        <input type="text" wire:model="contact_number" id="contact_number" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter contact number">
                        @error('contact_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address -->

                    <div>
                        <label for="city_municipality"
                            class="block mb-2 text-sm font-medium text-gray-900">City/Municipality</label>
                        <input type="text" wire:model="city_municipality" id="city_municipality" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter city or municipality">
                        @error('city_municipality')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Country</label>
                        <input type="text" wire:model="country" id="country" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter country">
                        @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Transaction Details ---------------------------------------------------------->

                    <!-- Rooms -->
                    <div>
                        <label for="reservation_id" class="block mb-2 text-sm font-medium text-gray-900">Room</label>
                        <select wire:model="reservation_id" id="reservation_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Room</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} - {{$room->base_rate}}</option>
                            @endforeach
                        </select>
                        @error('reservation_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Activities -->
                    <div>
                        <label for="activity_id" class="block mb-2 text-sm font-medium text-gray-900">Activity</label>
                        <select wire:model="activity_id" id="activity_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Activity</option>
                            @foreach ($activities as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }} - {{$activity->amount}}
                                </option>
                            @endforeach
                        </select>
                        @error('activity_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Check-in Date --}}
                    <div>
                        <label for="check_in_date" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                            Date</label>
                        <input type="date" wire:model="check_in_date" id="check_in_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            min="{{ now()->toDateString() }}" x-data
                            x-on:change="$refs.check_out_date.min = $event.target.value">
                        @error('check_in_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Check-out Date --}}
                    <div>
                        <label for="check_out_date" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                            Date</label>
                        <input type="date" wire:model="check_out_date" id="check_out_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            min="{{ now()->addDay()->toDateString() }}" x-ref="check_out_date">
                        @error('check_out_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Check-in Time --}}
                    <div>
                        <label for="check_in_time" class="block mb-2 text-sm font-medium text-gray-900">Check-in
                            Time</label>
                        <input type="time" wire:model="check_in_time" id="check_in_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('check_in_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Check-out Time --}}
                    <div>
                        <label for="check_out_time" class="block mb-2 text-sm font-medium text-gray-900">Check-out
                            Time</label>
                        <input type="time" wire:model="check_out_time" id="check_out_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('check_out_time')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Total Adults --}}
                    <div>
                        <label for="total_adults" class="block mb-2 text-sm font-medium text-gray-900">Total
                            Adults</label>
                        <input type="number" wire:model="total_adults" id="total_adults"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter number of adults">
                        @error('total_adults')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Total Kids --}}
                    <div>
                        <label for="total_kids" class="block mb-2 text-sm font-medium text-gray-900">Total Kids</label>
                        <input type="number" wire:model="total_kids" id="total_kids"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter number of kids">
                        @error('total_kids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Pets --}}
                    <div>
                        <label for="pets" class="block mb-2 text-sm font-medium text-gray-900">Number of pets (if
                            applicable)</label>
                        <input type="number" wire:model="pets" id="pets"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter number of pets">
                        @error('pets')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Information ------------------------------------------->

                    <!-- Payment Methods -->
                    <div>
                        <label for="payment_method_id" class="block mb-2 text-sm font-medium text-gray-900">Payment
                            Method</label>
                        <select wire:model="payment_method_id" id="payment_method_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">Select Payment Method</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->mode_of_payment_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Reference Number -->
                    <div>
                        <label for="payment_reference_number"
                            class="block mb-2 text-sm font-medium text-gray-900">Payment Reference Number</label>
                        <input type="text" wire:model="payment_reference_number" id="payment_reference_number"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter payment reference number">
                        @error('payment_reference_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Payment Screenshot Upload -->
                    <div class="sm:col-span-2">
                        <label for="payment_screenshot" class="block mb-2 text-sm font-medium text-gray-900">Upload
                            Image</label>
                        <input type="file" wire:model="payment_screenshot" id="payment_screenshot"
                            accept="image/png, image/jpeg"
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


                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-button onclick="history.back()" type="button"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>
                    <x-button class="mt-4" type="submit" wire:click="confirmCreate" wire:loading.attr="disabled">
                        Add Reservation
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Create Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Create Reservation') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to add this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="saveTransaction" wire:loading.attr="disabled">
                    {{ __('Create Reservation') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>