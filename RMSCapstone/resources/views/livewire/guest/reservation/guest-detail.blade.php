<!-- Guest Details Section -->
<div class="flex">
    <div class="w-full px-4">
        <div class="w-full rounded-xl shadow bg-gray-50 overflow-hidden">
            <!-- Section Title -->
            <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl text-center">
                Guest Details
            </div>
            <div class="px-6 pt-6 text-gray-700 text-md">
                Please provide your personal details below, including your name, email, contact number, and country. If you're bringing additional guests, you can add their information using the button below.
            </div>

            <div class="p-6 space-y-6">
                <!-- Guest Information Form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" wire:model="first_name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" wire:model="last_name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" wire:model="contact_number"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('contact_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" wire:model="country"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" />
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                      {{-- <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select wire:model="country"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                            <option value="">-- Select a country --</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <!-- Source of Hearing -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Where did you hear about us?</label>
                        <select wire:model="heard_from"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                            <option value="">Select an option</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Tiktok">Tiktok</option>
                            <option value="Youtube">Youtube</option>
                            <option value="Google">Google</option>
                        </select>
                        @error('heard_from')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Guests Section (Optional) -->
                <div class="flex flex-col space-y-2 w-full">
                    <div class="font-semibold">
                        Additional Guests (Optional)
                    </div>
                    <button
                        class="w-32 h-10 px-4 py-2 bg-white text-green-700 outline-green-800 text-sm rounded-md transition">
                        <i class="fas fa-plus mr-2"></i> Add Guest
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
