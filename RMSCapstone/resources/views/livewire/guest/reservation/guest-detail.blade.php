<!-- Guest Details Section -->
<div class="flex">
    <div class="w-full px-4">
        <div class="w-full rounded-xl shadow bg-gray-50 overflow-hidden">
            <!-- Section Title -->
            <div class="bg-green-700 text-white text-lg font-semibold px-4 py-3 rounded-t-xl text-center">
                Guest Details
            </div>
            <div class="px-6 pt-6 text-gray-700 text-md">
                Please provide your personal details below, including your name, email, contact number, and country. If
                you're bringing additional guests, you can add their information using the button below.
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

                    <!-- Displaying Added Guests -->
                    <div class="mt-6">
                        @if(count($guests) > 0)
                            <ul class="space-y-2">
                                @foreach($guests as $guest)
                                    <li class="flex justify-between items-center p-2 bg-gray-100 rounded-md">
                                        <span>{{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}</span>
                                        <div class="space-x-2">
                                            <button wire:click="editGuest({{ $loop->index }})"
                                                class="text-blue-500">Edit</button>
                                            <button class="text-red-500">Delete</button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No guests added yet.</p>
                        @endif
                    </div>


                    <!-- Button to open modal -->
                    <div class="mt-4">
                        <button type="button" wire:click="openGuestModal"
                            class="inline-flex items-center px-3 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-1"></i> Add Guest
                        </button>
                    </div>

                    @if($showEditModal)
                        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                                <h2 class="text-lg font-semibold mb-4">Edit Guest Details</h2>

                                <!-- Guest Name -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">First Name</label>
                                        <input type="text" wire:model.defer="editingGuest.guest_first_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('editingGuest.guest_first_name') <span
                                        class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Middle Name</label>
                                        <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('editingGuest.guest_middle_name') <span
                                        class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Last Name</label>
                                        <input type="text" wire:model.defer="editingGuest.guest_last_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('editingGuest.guest_last_name') <span
                                        class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Suffix</label>
                                        <input type="text" wire:model.defer="editingGuest.guest_suffix"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('editingGuest.guest_suffix') <span
                                        class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Guest Type -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Guest Type</label>
                                    <select wire:model.defer="editingGuest.guest_type_id"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Guest Type</option>
                                        @foreach($guest_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editingGuest.guest_type_id') <span
                                    class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Gender</label>
                                    <select wire:model.defer="editingGuest.guest_gender"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('editingGuest.guest_gender') <span
                                    class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Residency -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Residency</label>
                                    <select wire:model.defer="editingGuest.guest_residency"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Residency</option>
                                        <option value="local">Local</option>
                                        <option value="foreigner">Foreigner</option>
                                    </select>
                                    @error('editingGuest.guest_residency') <span
                                    class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Country of Origin -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Country of Origin</label>
                                    <input type="text" wire:model.defer="editingGuest.guest_country_of_origin"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                    @error('editingGuest.guest_country_of_origin') <span
                                    class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-2 mt-6">
                                    <button wire:click="$set('showEditModal', false)"
                                        class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                        Cancel
                                    </button>
                                    <button wire:click="updateGuest"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif



                    <!-- Modal -->
                    @if($showGuestModal)
                        <div id="guestModal"
                            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                                <h2 class="text-lg font-semibold mb-4">Enter Guest Details</h2>

                                <!-- Guest Name -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">First Name</label>
                                        <input type="text" wire:model="guest_first_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('guest_first_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Middle Name</label>
                                        <input type="text" wire:model="guest_middle_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('guest_middle_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Last Name</label>
                                        <input type="text" wire:model="guest_last_name"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('guest_last_name') <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Suffix</label>
                                        <input type="text" wire:model="guest_suffix"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('guest_suffix') <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Guest Type -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Guest Type</label>
                                    <select wire:model="guest_type_id"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Guest Type</option>
                                        @foreach($guest_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('guest_type_id') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Gender</label>
                                    <select wire:model="guest_gender"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('guest_gender') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Residency -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Residency</label>
                                    <select wire:model="guest_residency"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Residency</option>
                                        <option value="local">Local</option>
                                        <option value="foreigner">Foreigner</option>
                                    </select>
                                    @error('guest_residency') <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Country of Origin -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Country of Origin</label>
                                    <input type="text" wire:model="guest_country_of_origin"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                    @error('guest_country_of_origin') <span
                                        class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Actions -->
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" wire:click="closeGuestModal"
                                        class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                        Cancel
                                    </button>
                                    <button type="button" wire:click="addMultipleGuests"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white transition duration-150 ease-in-out">
                                        Add Guests
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>