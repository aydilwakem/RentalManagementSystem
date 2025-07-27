<!-- Guest Details Section -->
<div class="flex">
    <div class="w-full">
        <div class="w-full rounded-xl shadow bg-gray-50 overflow-hidden">
            <!-- Section Title -->
            <div class="bg-green-800 text-white text-lg font-semibold px-4 py-3 rounded-t-xl text-center">
                Guest Details
            </div>
            <div class="px-6 pt-6 text-gray-700 text-md">
                Please provide your personal details below, these will be used as the main reference for your
                reservation. If
                you're bringing additional guests, you can add their information using the option below.
            </div>

            <div class="p-4 space-y-6">

                <!-- Guest Information Form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="first_name" placeholder="Ex. Juan"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name" placeholder="Ex. Mercado"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" placeholder="Ex. juan.delacruz@example.com"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="contact_number" placeholder="Ex. +63 912 345 6789"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('contact_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Country -->
                    <!--TODO: Make country field dropdown of pre populated countries (plugin) -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="country" placeholder="Ex. Philippines"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <select wire:model="country"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="">-- Select a country --</option>
                            @foreach ($countries as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                        @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div> --}}



                    <!-- Company Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name (Optional)</label>
                        <input type="text" wire:model="company_name" placeholder="Ex. ABC Corporation"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <!-- Source of Hearing -->
                        <label class="block text-sm font-medium text-gray-700 mb-1">Where did you hear about us? <span
                                class="text-red-500">*</span></label>
                        <select wire:model="heard_from"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600">
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


                <div class="col-span-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Are you bringing in pets?
                        <!-- Info Icon with Tooltip -->
                        <div class="relative group inline-block">
                            <i class="fas fa-info-circle text-gray-500 text-sm cursor-pointer dark:text-gray-200"></i>

                            <!-- Tooltip -->
                            <div
                                class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-sm text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                                Please note that there is a pet fee of PHP300/pet/night and we require that pets are
                                vaccinated and on a leash when outdoors for safety of all guests. Kindly send a soft
                                copy of the vaccination card @thecanopyfarmph.
                            </div>
                        </div>
                    </label>

                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 dark:text-gray-200">No</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="bringing_pets" wire:model.live="bringingPets" value="1"
                                class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition">
                            </div>
                            <div
                                class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                        <span class="text-gray-700 dark:text-gray-200">Yes</span>
                    </div>
                    @error('bringingPets')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                @if ($bringingPets)
                    <div class="mt-4 col-span-1">
                        <label for="breed" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Pet Breed
                        </label>
                        <input type="text" id="breed" wire:model="breed"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="e.g., Labrador">
                        @error('breed')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        <button type="button" wire:click="addMultiplePets"
                            class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Pet</button>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-md font-semibold text-gray-800 dark:text-gray-200">Added Pets:</h3>
                        <ul class="list-disc ml-6">
                            @foreach ($pets as $index => $pet)
                                <li>Pet #{{ $index + 1 }}: {{ $pet['breed'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif







                <!-- Additional Guests Section (Optional) -->
                <div class="flex flex-col space-y-2 w-full">

                    <div class="font-semibold text-gray-700">
                        Additional Guests (Optional)
                    </div>

                    <!-- Displaying Added Guests -->
                    <div class="mt-6">
                        @if (count($guests) > 0)
                            <ol
                                class="space-y-3 list-decimal pl-6 text-gray-700 mb-3 p-3 bg-white rounded-2xl border border-gray-300">
                                @foreach ($guests as $guest)
                                    <li class="me-2">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-gray-700 flex">
                                                {{ $guest['guest_first_name'] }} {{ $guest['guest_last_name'] }}
                                            </div>
                                            <div class="space-x-5 flex items-center">
                                                <button wire:click="editGuest({{ $loop->index }})"
                                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-800 hover:underline font-sm transition duration-150">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </button>

                                                <button wire:click="deleteGuest({{ $loop->index }})"
                                                    class="inline-flex items-center text-red-500 hover:text-red-700 hover:underline font-sm transition duration-150">
                                                    <i class="fas fa-trash-alt mr-1"></i>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <p>No guests added.</p>
                        @endif
                    </div>

                    <!-- Button to open modal -->
                    @if (count($guests) < $total_pax)
                        <div class="mt-4">
                            <x-button type="button" wire:click="openGuestModal">
                                <i class="fas fa-plus mr-1"></i> Add Guest
                            </x-button>
                        </div>
                    @endif

                    <!------------------------------ MODALS ------------------------------------>

                    <!-- Edit Modal -->
                    @if ($showEditModal)
                        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                                <h2 class="text-xl font-bold mb-4 text-center text-green-700">Edit Guest Details</h2>

                                <!-- Guest Name -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">First Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="editingGuest.guest_first_name"
                                            placeholder="Ex. Juan"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('editingGuest.guest_first_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Middle Name </label>
                                        <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                                            placeholder="Ex. Mercado"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('editingGuest.guest_middle_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Last Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="editingGuest.guest_last_name"
                                            placeholder="Ex. Dela Cruz"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('editingGuest.guest_last_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Suffix</label>
                                        <input type="text" wire:model.defer="editingGuest.guest_suffix"
                                            placeholder="Ex. Jr., Sr., III"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('editingGuest.guest_suffix')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Guest Type -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Guest Type <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model.defer="editingGuest.guest_type_id"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Guest Type</option>
                                        @foreach ($guest_types as $type)
                                            <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('editingGuest.guest_type_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Gender <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model.defer="editingGuest.guest_gender"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('editingGuest.guest_gender')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Residency -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Residency <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model.defer="editingGuest.guest_residency"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Residency</option>
                                        <option value="local">Local</option>
                                        <option value="foreigner">Foreigner</option>
                                    </select>
                                    @error('editingGuest.guest_residency')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Country of Origin -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Country of Origin <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model.defer="editingGuest.guest_country_of_origin"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                    @error('editingGuest.guest_country_of_origin')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-2 mt-6">
                                    <button wire:click="$set('showEditModal', false)"
                                        class="mt-4 block px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150">
                                        Cancel
                                    </button>
                                    <button wire:click="updateGuest"
                                        class="mt-4 block px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Add Guest Modal -->
                    @if ($showGuestModal)
                        <div id="guestModal"
                            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                                <h2 class="text-xl font-bold mb-4 text-center text-green-700">Enter Additional Guest
                                    Details</h2>

                                <!-- Guest Name -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">First Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="guest_first_name" placeholder="Ex. Juan"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('guest_first_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Middle Name</label>
                                        <input type="text" wire:model="guest_middle_name" placeholder="Ex. Mercado"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('guest_middle_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Last Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="guest_last_name" placeholder="Ex. Dela Cruz"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                        @error('guest_last_name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Suffix</label>
                                        <input type="text" wire:model="guest_suffix" placeholder="Ex. Jr., Sr., III"
                                            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        @error('guest_suffix')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Guest Type -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Guest Type <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model="guest_type_id"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Guest Type</option>
                                        @foreach ($guest_types as $type)
                                            <option value="{{ $type->id }}">{{ ucfirst($type->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('guest_type_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Gender <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model="guest_gender"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Prefer not to say</option>
                                    </select>
                                    @error('guest_gender')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Residency -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Residency <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model="guest_residency"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                        <option value="">Select Residency</option>
                                        <option value="local">Local</option>
                                        <option value="foreigner">Foreigner</option>
                                    </select>
                                    @error('guest_residency')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Country of Origin -->
                                <div class="mt-4">
                                    <label class="block text-sm text-gray-700">Country of Origin <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model="guest_country_of_origin" placeholder="Ex. Philippines"
                                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                    @error('guest_country_of_origin')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>


                                <!-- Actions -->
                                <div class="flex justify-between gap-2 mt-6">
                                    <!-- Cancel Button -->
                                    <x-ghost-button type="button" wire:click="closeGuestModal">
                                        Cancel
                                    </x-ghost-button>

                                    <!-- Add Guest Button -->
                                    <x-button type="button" wire:click="addMultipleGuests" wire:loading.attr="disabled">
                                        <div class="flex items-center justify-center">
                                            <!-- Spinner -->
                                            <span wire:loading class="mr-2" wire:target="addMultipleGuests">
                                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                                    </path>
                                                </svg>
                                            </span>

                                            <!-- Button Text -->
                                            <span wire:loading.remove wire:target="addMultipleGuests">
                                                Add Guest
                                            </span>
                                        </div>
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>

                @error('guests')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>