<!-- Guest Details Section -->
<div class="flex">
    <div class="w-full">
        <div class="w-full rounded-xl shadow bg-gray-50 overflow-hidden">
            <!-- Section Title -->
            <div class="bg-green-800 text-white text-lg font-semibold px-4 py-3 rounded-t-xl text-center">
                Guest Details
            </div>
            <div class="px-6 pt-6 text-gray-800 text-md">
                Please provide your personal details below, these will be used as the main reference for your
                reservation. If
                you're bringing additional guests, you can add their information using the option below.
            </div>

            <div class="p-4 space-y-6">

                <!-- Guest Information Form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">First Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="first_name" placeholder="Ex. Juan"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Middle Name</label>
                        <input type="text" wire:model="middle_name" placeholder="Ex. Mercado"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Last Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="last_name" placeholder="Ex. Dela Cruz"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" placeholder="Ex. juan.delacruz@example.com"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="col-span-1">
                        <label for="phone" class="block text-sm font-medium text-gray-800 mb-1">Contact Number <span
                                class="text-red-500">*</span></label>
                        <input type="tel" inputmode="numeric" maxlength="11" id="phone" name="phone"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="contact_number"
                            placeholder="Ex. 912 345 6789"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        {{-- Hidden input to store country code --}}
                        <input type="hidden" id="country_code" name="country_code" wire:model="country_code" />
                        @error('contact_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <script>
                        const input = document.querySelector("#phone");
                        window.intlTelInput(input, {
                            initialCountry: "ph", // default Philippines
                            preferredCountries: ["ph", "us", "sg"], // top of the list
                            separateDialCode: true, // shows dial code separately
                            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                        });

                        // Set initial country code on mount
                        document.getElementById('country_code').value = '+' + iti.getSelectedCountryData().dialCode;
                        @this.set('country_code', '+' + iti.getSelectedCountryData().dialCode);

                        // Update country code when the country changes
                        input.addEventListener('countrychange', function() {
                            const code = '+' + iti.getSelectedCountryData().dialCode;
                            document.getElementById('country_code').value = code;
                            @this.set('country_code', code);
                        });

                        // if saving as complete number including the country code
                        // const iti = window.intlTelInput(input, {
                        //     initialCountry: "ph",
                        //     separateDialCode: true,
                        //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                        // });

                        // pang debug
                        // document.querySelector("form").addEventListener("submit", function(e) {
                        //     const fullNumber = iti.getNumber();
                        //     console.log(fullNumber); // e.g. +639951189968
                        // });
                    </script>

                    <!-- Country -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Country <span
                                class="text-red-500">*</span></label>
                        <select wire:model="country"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600">
                            <option value="" disabled selected>Select a country</option>
                            @foreach ($countries as $countryOption)
                                <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Company Name</label>
                        <input type="text" wire:model="company_name" placeholder="Ex. ABC Corporation"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Source of Hearing -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Where did you hear about us? <span
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

                <!-- Special Requests -->
                <div>
                    <label class="block text-sm font-medium text-gray-800 mb-1">
                        Special Requests
                    </label>

                    @foreach ($special_requests as $index => $request)
                        <div class="mb-2 flex items-center gap-2">
                            <textarea type="text" wire:model="special_requests.{{ $index }}.request" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none"
                                placeholder="Requests are still subject for approval">
                                        </textarea>
                        </div>
                        <button wire:click.prevent="removeSpecialRequest({{ $index }})"
                            class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        <button wire:click.prevent="addSpecialRequest"
                            class=" text-sm text-green-600 hover:text-green-800">+ Add Request</button>
                        @error("special_requests.$index.request")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    @endforeach

                </div>

                <!-- Pets Toggle -->
                <div class="w-1/2">
                    <div class="col-span-1 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-1">
                                Are you bringing pets?
                            </label>
                            <div class="flex items-center gap-4">
                                <span class="text-gray-800 dark:text-gray-200">No</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="bringing_pets" wire:model.live="bringingPets"
                                        value="1" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-600 transition peer-focus:ring-2 peer-focus:ring-green-500">
                                    </div>
                                    <div
                                        class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                    </div>
                                </label>
                                <span class="text-gray-800 dark:text-gray-200">Yes</span>
                            </div>
                            @error('bringingPets')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                @if ($bringingPets)
                    <!-- Displaying Added Pets -->
                    <div class="mt-6">

                        <!-- Display remaining capacity -->
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200 mb-2">
                            Added Pets: {{ count($pets) }}/{{ $this->maxPetsAllowed }}
                        </h3>

                        @if (count($pets) > 0)
                            <ol
                                class="space-y-3 list-decimal pl-6 text-gray-700 mb-3 p-3 bg-white rounded-2xl border border-gray-300">
                                @foreach ($pets as $index => $pet)
                                    <li class="me-2">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-gray-700 flex">
                                                Pet #{{ $index + 1 }}: {{ $pet['breed'] }}
                                            </div>
                                            <div class="space-x-5 flex items-center">
                                                <button wire:click="editGuestPet({{ $index }})"
                                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-800 hover:underline font-sm transition duration-150">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </button>

                                                <button wire:click="removeGuestPet({{ $index }})"
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
                            <p class="text-gray-500">No pets added.</p>
                        @endif
                    </div>

                    <!-- Button to open pet modal -->
                    @if (count($pets) < $this->maxPetsAllowed)
                        <div class="mt-4">
                            <x-button type="button" wire:click="openPetModal">
                                <i class="fas fa-paw mr-1"></i> Add Pet
                            </x-button>
                        </div>
                    @endif
                @endif

                <!-- Additional Guests Section (Optional) -->
                <div class="flex flex-col space-y-2 w-full">

                    <div class="block text-sm font-medium text-gray-800">
                        Additional Guests (Optional)
                        @php
                            $maxGuestsAllowed = $total_pax - 1;
                            $guestCount = count($guests);
                        @endphp

                        @if ($guestCount > 0 || $maxGuestsAllowed > 0)
                            <span class="ml-2 text-sm font-normal text-gray-500">
                                ({{ $guestCount }}/{{ $maxGuestsAllowed }} {{ Str::plural('guest', $guestCount) }})
                            </span>
                        @endif
                    </div>

                    <!-- Displaying Added Guests -->
                    <div class="mt-6">
                        @if (count($guests) > 0)
                            <ol
                                class="space-y-3 list-decimal pl-6 text-gray-800 mb-3 p-3 bg-white rounded-2xl border border-gray-300">
                                @foreach ($guests as $guest)
                                    <li class="me-2">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-gray-800 flex">
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
                            <p class="text-gray-500 italic">No guests added.</p>
                        @endif
                    </div>

                    <!-- Button to open modal -->
                    @if (count($guests) < $total_pax - 1)
                        <div class="mt-6">
                            <x-button type="button" wire:click="openGuestModal">
                                <i class="fas fa-plus mr-1"></i> Add Guest
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>

            <!------------------------------ MODALS ------------------------------------>

            <!-- Add Guest Modal -->
            @if ($showGuestModal)
                <div id="guestModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                        <h2 class="text-xl font-bold mb-4 text-center text-green-700">Enter Additional Guest
                            Details</h2>

                        <!-- Guest Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-800">First Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="guest_first_name" placeholder="Ex. Juan"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('guest_first_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Middle Name</label>
                                <input type="text" wire:model="guest_middle_name" placeholder="Ex. Mercado"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('guest_middle_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Last Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="guest_last_name" placeholder="Ex. Dela Cruz"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('guest_last_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Suffix</label>
                                <input type="text" wire:model="guest_suffix" placeholder="Ex. Jr., Sr., III"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('guest_suffix')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Guest Type -->
                        <div class="mt-4">
                            <label class="block text-sm text-gray-800">Guest Type <span
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
                            <label class="block text-sm text-gray-800">Gender <span
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
                            <label class="block text-sm text-gray-800">Residency <span
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
                            <label class="block text-sm text-gray-800">Country of Origin <span
                                    class="text-red-500">*</span></label>

                            <select wire:model="guest_country_of_origin"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                <option value="" disabled>Select a country</option>
                                @foreach ($countries as $countryOption)
                                    <option value="{{ $countryOption }}">{{ $countryOption }}</option>
                                @endforeach
                            </select>

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
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
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


            <!-- Edit Modal -->
            @if ($showEditModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                        <h2 class="text-xl font-bold mb-4 text-center text-green-700">Edit Guest Details</h2>

                        <!-- Guest Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-800">First Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="editingGuest.guest_first_name"
                                    placeholder="Ex. Juan"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('editingGuest.guest_first_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Middle Name </label>
                                <input type="text" wire:model.defer="editingGuest.guest_middle_name"
                                    placeholder="Ex. Mercado"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md">
                                @error('editingGuest.guest_middle_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Last Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="editingGuest.guest_last_name"
                                    placeholder="Ex. Dela Cruz"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('editingGuest.guest_last_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-800">Suffix</label>
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
                            <label class="block text-sm text-gray-800">Guest Type <span
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
                            <label class="block text-sm text-gray-800">Gender <span
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
                            <label class="block text-sm text-gray-800">Residency <span
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
                            <label class="block text-sm text-gray-800">Country of Origin <span
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
                                class="mt-4 block px-4 py-2 text-gray-800 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150">
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

            <!-- Add Pet Modal -->
            @if ($addPetModal)
                <div id="guestModal"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                        <h2 class="text-xl font-bold mb-4 text-center text-green-700">Enter Pet
                            Details</h2>

                        <!-- Pet Breed -->
                        <div>
                            <label class="block text-sm text-gray-700">Pet Breed<span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="breed" placeholder="Ex. Labrador"
                                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                            @error('breed')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Actions -->
                        <div class="flex justify-between gap-2 mt-6">
                            <!-- Cancel Button -->
                            <x-ghost-button type="button" wire:click="closePetModal">
                                Cancel
                            </x-ghost-button>

                            <!-- Add Pet Button -->
                            <x-button type="button" wire:click="addMultiplePets" wire:loading.attr="disabled">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading class="mr-2" wire:target="addMultiplePets">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="addMultiplePets">
                                        Add Pet
                                    </span>
                                </div>
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Modal -->
            @if ($showEditPetModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[650px] max-h-[100vh] overflow-y-auto">
                        <h2 class="text-xl font-bold mb-4 text-center text-green-700">Edit Pet Details</h2>

                        <!-- Breed Field -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm text-gray-800">Breed Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="editingPet.breed" placeholder="Ex. Labrador"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                                @error('editingPet.breed')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-2 mt-6">
                            <button wire:click="$set('showEditPetModal', false)"
                                class="px-4 py-2 text-gray-800 bg-gray-200 hover:bg-gray-300 border border-transparent font-semibold rounded-md text-xs uppercase transition ease-in-out duration-150">
                                Cancel
                            </button>
                            <button wire:click="updatePet"
                                class="px-4 py-2 bg-green-700 bg-opacity-85 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
