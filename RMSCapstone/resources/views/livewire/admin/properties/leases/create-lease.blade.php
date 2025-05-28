<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Lease') }}
        </h2>
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Add New Lease</h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.leases') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form Container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 md:grid-cols-2 sm:gap-6">

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">
                            Start Lease <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.live="start_date" id="start_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                        @error('start_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">
                            End Lease Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model.live="end_date" id="end_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                        @error('end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Select House -->
                    <div>
                        <label for="house_id" class="block mb-2 text-sm font-medium text-gray-900">
                            Select House <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="house_id" id="house_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                            <option value="">Select House</option>
                            @foreach ($houses as $house)
                                <option value="{{ $house->id }}" @if ($house->isBooked) disabled @endif>
                                    {{ $house->name_number }} @if ($house->isBooked)
                                        - (Leased)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('house_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Assign Tenant -->
                    <div>
                        <label for="selectedTenant" class="block mb-2 text-sm font-medium text-gray-900">
                            Assign Tenant <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="selectedTenant" id="selectedTenant"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                            <option value="">Select Tenant</option>
                            @foreach ($tenants as $tenant)
                                <option value="{{ $tenant->id }}" @if ($tenant->isLeased) disabled @endif>
                                    {{ $tenant->first_name }} {{ $tenant->last_name }}
                                    @if ($tenant->isLeased)
                                        - (Leased: {{ $tenant->leasedPropertyName ?? 'Unnamed Property' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('selectedTenant')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Total Pax in House -->
                    <div>
                        <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">
                            Total People in the House <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="pax" id="pax" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5"
                            placeholder="Ex. 5">
                        @error('pax')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Monthly Rent -->
                    <div>
                        <label for="monthly_rent" class="block mb-2 text-sm font-medium text-gray-900">
                            Monthly Rent <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live="monthly_rent" id="monthly_rent"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600"
                            placeholder="Ex. 8,500.00">
                        @error('monthly_rent')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Transaction Status -->
                    <div class="mb-4">
                        <label for="transaction_status" class="block mb-2 text-sm font-medium text-gray-900">
                            Lease Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="transaction_status" x-model="showPlanned" id="transaction_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                            <option value="">Select Lease Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="ongoing">On-going</option>
                            <option value="terminated">Terminated</option>
                        </select>
                        @error('transaction_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Total Amount (calculated) -->
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Total Rent for Lease Term</label>
                        <input type="text" value="₱{{ number_format($total_amount, 2) }}" disabled
                            class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 cursor-not-allowed">
                    </div>
            </form>
        </div>

        <!-- Actions Buttons -->
        <div class="flex justify-between items-center space-y-2 mt-6">
            <x-ghost-button onclick="history.back()" type="button">
                Cancel
            </x-ghost-button>
            <x-button wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                Create Lease
            </x-button>
        </div>

        <!-- Create Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Create Lease') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to create this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="saveLease" wire:loading.attr="disabled">
                    {{ __('Create Lease') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

    </div>
</div>
