<!-- day-tour-step-two.blade.php -->
<div class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800">Guest Information</h2>

    <!-- Guest Count Section -->
    @if($selectedTour && $selectedRate)
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Guest Count</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Adult Selector -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="text-center mb-3">
                        <h4 class="font-semibold text-gray-800">Adults</h4>
                        <p class="text-sm text-gray-600">12 years old and above</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" wire:click="decrementAdult"
                            class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $adultCount <= 1 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>

                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $adultCount }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                ₱{{ number_format($selectedRate->adult_rate, 2) }} each
                            </div>
                        </div>

                        <button type="button" wire:click="incrementAdult"
                            class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Child Selector -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="text-center mb-3">
                        <h4 class="font-semibold text-gray-800">Children</h4>
                        <p class="text-sm text-gray-600">3-11 years old</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" wire:click="decrementKid"
                            class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $kidCount <= 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>

                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $kidCount }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                ₱{{ number_format($selectedRate->kid_rate, 2) }} each
                            </div>
                        </div>

                        <button type="button" wire:click="incrementKid"
                            class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Total Guests Display -->
            <div class="mt-4 text-center">
                <p class="text-lg font-semibold text-gray-700">
                    Total Guests: <span class="text-green-600">{{ $totalGuests }}</span>
                </p>
                {{-- @if($selectedRate && $totalGuests > $selectedRate->max_guests)
                <p class="text-red-500 text-sm mt-1">
                    Maximum guests for this rate is {{ $selectedRate->max_guests }}
                </p>
                @endif --}}
            </div>

            <!-- Price Preview -->
            <div class="mt-4 p-4 bg-white rounded-lg border border-green-200">
                <h4 class="font-semibold mb-3 text-gray-800">Price Summary</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Adults ({{ $adultCount }}):</span>
                        <span class="font-semibold">₱{{ number_format($adultCount * $selectedRate->adult_rate, 2) }}</span>
                    </div>
                    @if($kidCount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Children ({{ $kidCount }}):</span>
                            <span class="font-semibold">₱{{ number_format($kidCount * $selectedRate->kid_rate, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t pt-2 mt-2">
                        <span class="font-semibold text-gray-800">Subtotal:</span>
                        <span class="font-bold text-green-600">₱{{ number_format($this->subtotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Primary Guest Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">First Name *</label>
            <input type="text" wire:model="first_name" class="border border-gray-300 rounded px-3 py-2 w-full">
            @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Middle Name</label>
            <input type="text" wire:model="middle_name" class="border border-gray-300 rounded px-3 py-2 w-full">
            @error('middle_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Last Name *</label>
            <input type="text" wire:model="last_name" class="border border-gray-300 rounded px-3 py-2 w-full">
            @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email *</label>
            <input type="email" wire:model="email" class="border border-gray-300 rounded px-3 py-2 w-full">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
            <input type="tel" wire:model="contact_number" class="border border-gray-300 rounded px-3 py-2 w-full">
            @error('contact_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Where did you hear about us? *</label>
            <select wire:model="heard_from" class="border border-gray-300 rounded px-3 py-2 w-full">
                <option value="">Select an option</option>
                <option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="Tiktok">Tiktok</option>
                <option value="Youtube">Youtube</option>
                <option value="Google">Google</option>
            </select>
            @error('heard_from') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>


</div>