<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Edit Tenant') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <div class="relative flex items-center mb-6">
                <!-- Room Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Tenant Details</h2>

                <!-- Back Button -->
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Tenant Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 col-span-2">

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">First
                                Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="first_name" id="first_name"
                                placeholder="Ex. Juan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                required>
                            @error('first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middle_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Middle
                                Name</label>
                            <input type="text" wire:model="middle_name" id="middle_name"
                                placeholder="Ex. Mercado"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('middle_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Last
                                Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="last_name" id="last_name"
                                placeholder="Ex. Dela Cruz"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                                required>
                            @error('last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Suffix -->
                    <div>
                        <label for="suffix" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Suffix</label>
                        <input type="text" wire:model="suffix" id="suffix"
                            placeholder="Ex. Jr., Sr., III"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('suffix')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" id="email"
                            placeholder="Ex. juan.delacruz@example.com"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label for="contact_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Contact
                            Number</label>
                        <input type="text" wire:model="contact_number" id="contact_number"
                            placeholder="Ex. 0912 345 6789"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            required>
                        @error('contact_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Company
                            Name</label>
                        <input type="text" wire:model="company_name" id="company_name"
                            placeholder="Ex. ABC Corporation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('company_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- City/Municipality -->
                    <div>
                        <label for="city_municipality"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">City/Municipality</label>
                        <input type="text" wire:model="city_municipality" id="city_municipality"
                            placeholder="Ex. Taguig City"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('city_municipality')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Country <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="country" id="country"
                            placeholder="Ex. Philippines"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                </div>

                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="newImage"
                        wire:click="confirmEdit({{ $tenant->id }})">
                        Save Changes
                    </x-button>
                </div>


            </form>
            <!-- Edit Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmEditItem">
                <x-slot name="title">
                    {{ __('Edit Tenant') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to save changes to this tenant?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="updateTenant({{ $tenant->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Save Changes') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
