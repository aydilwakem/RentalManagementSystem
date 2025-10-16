<div class="space-y-8">

    @if ($selectedTour && $selectedRate)
    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">

        <h2 class="text-xl font-bold text-green-700 border-b border-gray-200 pb-3 mb-6">
            Select Guests
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-4 rounded-xl border border-green-400/50 bg-green-50 shadow-sm">
                <div class="text-center mb-3">
                    <h4 class="text-lg font-bold text-green-700">Adults</h4>
                    <p class="text-xs text-gray-600">12 years old and above</p>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button" wire:click="decrementAdult"
                        class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center
                               hover:bg-red-200 transition text-gray-700 disabled:opacity-40 disabled:cursor-not-allowed" {{ $adultCount <=1 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                            </path>
                        </svg>
                    </button>

                    <div class="text-center mx-4">
                        <div class="text-3xl font-extrabold text-green-700">{{ $adultCount }}
                        </div>
                        <div class="text-xs text-gray-700 mt-1">
                            ₱{{ number_format($selectedRate->adult_rate, 2) }} each
                        </div>
                    </div>

                    <button type="button" wire:click="incrementAdult" class="w-10 h-10 rounded-full bg-green-700 flex items-center justify-center
                               hover:bg-green-700 transition text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 rounded-xl border border-blue-400/50 bg-blue-50 shadow-sm">
                <div class="text-center mb-3">
                    <h4 class="text-lg font-bold text-blue-700">Children</h4>
                    <p class="text-xs text-gray-600">3-11 years old</p>
                </div>

                <div class="flex items-center justify-between">
                    <button type="button" wire:click="decrementKid"
                        class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center
                               hover:bg-red-200 transition text-gray-700 disabled:opacity-40 disabled:cursor-not-allowed" {{ $kidCount <=0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                            </path>
                        </svg>
                    </button>

                    <div class="text-center mx-4">
                        <div class="text-3xl font-extrabold text-green-700">{{ $kidCount }}
                        </div>
                        <div class="text-xs text-gray-700 mt-1">
                            ₱{{ number_format($selectedRate->kid_rate, 2) }} each
                        </div>
                    </div>

                    <button type="button" wire:click="incrementKid" class="w-10 h-10 rounded-full bg-green-700 flex items-center justify-center
                               hover:bg-green-700 transition text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-6">

            {{-- @if ($selectedRate && $totalGuests > $selectedRate->max_guests)
            <div class="p-3 bg-red-50 rounded-lg border border-red-300 text-center shadow-sm">
                <p class="text-red-600 text-sm font-medium">
                    Maximum guests for this rate is **{{ $selectedRate->max_guests }}**. Please adjust your selection.
                </p>
            </div>
            @endif --}}

            <div class="p-4 bg-white rounded-xl border border-gray-300 shadow-sm">
                <h4 class="font-bold mb-3 text-gray-800 border-b border-gray-200 pb-2">
                    Price Summary</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Adults ({{ $adultCount }} x
                            ₱{{ number_format($selectedRate->adult_rate, 2) }}):</span>
                        <span class="font-semibold text-gray-800">₱{{ number_format($adultCount *
                            $selectedRate->adult_rate, 2) }}</span>
                    </div>
                    @if ($kidCount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Children ({{ $kidCount }} x
                            ₱{{ number_format($selectedRate->kid_rate, 2) }}):</span>
                        <span class="font-semibold text-gray-800">₱{{ number_format($kidCount * $selectedRate->kid_rate,
                            2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-green-200 pt-3 mt-3">
                        <span class="font-bold text-base text-gray-800">Subtotal:</span>
                        <span class="font-extrabold text-xl text-green-700">₱{{ number_format($this->subtotal, 2)
                            }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-green-700 border-b border-gray-200 pb-3 mb-6">
            Guest Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="first_name" placeholder="Ex. Juan"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm">
                @error('first_name')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input type="text" wire:model="middle_name" placeholder="Ex. Mercado"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm">
                @error('middle_name')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Last Name <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm">
                @error('last_name')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" wire:model="email" placeholder="Ex. juan.delacruz@example.com"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm">
                @error('email')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Contact Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" inputmode="numeric" maxlength="11" id="phone" name="phone"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="contact_number"
                    placeholder="Ex. 912 345 6789"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm" />

                {{-- Hidden input to store country code --}}
                <input type="hidden" id="country_code" name="country_code" wire:model="country_code" />
                @error('contact_number')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Where did you hear about us? <span class="text-red-500">*</span>
                </label>
                <select wire:model="heard_from"
                    class="border border-gray-300 rounded-lg px-4 py-2 w-full focus:ring-green-500 focus:border-green-500 focus:ring-1 transition shadow-sm">
                    <option value="">Select an option</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Youtube">Youtube</option>
                    <option value="Google">Google</option>
                </select>
                @error('heard_from')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>