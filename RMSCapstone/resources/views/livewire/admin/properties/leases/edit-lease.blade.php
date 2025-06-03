 <div>
     <!-- Header -->
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Edit Lease') }}
         </h2>
     </x-slot>

     <!-- Body Container -->
     <div class="py-3">
         <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6">

             <div class="relative flex items-center mb-4">
                 <!-- Title -->
                 <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Edit Lease Details</h2>

                 <!-- Back Button -->
                 <button onclick="history.back()" wire:navigate
                     class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                     <span class="leading-none translate-y-[-3px]">&times;</span>
                 </button>
             </div>

             <!-- Form Container -->
             <form wire:submit.prevent="">
                 <div class="grid gap-4 md:grid-cols-2 sm:gap-6">

                     <!-- Select House -->
                     <div>
                         <label for="house_id" class="block mb-2 text-sm font-medium text-gray-900">Select House</label>
                         <select wire:model="house_id" id="house_id" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                             <option value="">Select House</option>
                             @foreach ($houses as $house)
                                 <option value="{{ $house->id }}"
                                     @if ($house->is_leased) disabled class="text-gray-400" @endif>
                                     {{ $house->name_number }}
                                     @if ($house->is_leased)
                                         (Leased)
                                     @endif
                                 </option>
                             @endforeach
                         </select>
                         @error('house_id')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                     <!-- Assigned Tenant -->
                     <div>
                         <label for="selectedTenant" class="block mb-2 text-sm font-medium text-gray-900">Assign Tenant
                         </label>
                         <select wire:model="selectedTenant" id="selectedTenant" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                             <option value="">Select Tenants</option>
                             @foreach ($tenants as $tenant)
                                 <option value="{{ $tenant->id }}"
                                     @if ($tenant->has_active_lease) disabled class="text-gray-400" @endif>
                                     {{ $tenant->first_name . ' ' . $tenant->last_name ?? 'Tenant #' . $tenant->id }}
                                     @if ($tenant->has_active_lease)
                                         (Leased: {{ $tenant->leased_property ?? 'Unnamed Property' }})
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
                         <label for="pax" class="block mb-2 text-sm font-medium text-gray-900">Total People in the
                             House</label>
                         <input type="number" wire:model="pax" id="pax" min="0" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                         @error('pax')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                         @enderror
                     </div>


                     <!-- Monthly Rent -->
                     <div>
                         <label for="monthly_rent" class="block mb-2 text-sm font-medium text-gray-900">Monthly
                             Rent</label>
                         <input type="amount" wire:model.live="monthly_rent" id="monthly_rent" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                         @error('monthly_rent')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                     <!-- Start Date -->
                     <div>
                         <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Start
                             Lease</label>
                         <input type="date" wire:model.live="start_date" id="start_date" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                         @error('start_date')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                     <!-- End Date -->
                     <div>
                         <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">End Lease Date
                         </label>
                         <input type="date" wire:model.live="end_date" id="end_date" disabled
                             class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                         @error('end_date')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                         @enderror
                     </div>

                     <!-- Transaction Status -->
                     <div class="mb-4">
                         <label for="transaction_status" class="block mb-2 text-sm font-medium text-gray-900">Lease
                             Status <span class="text-red-500">*</span></label>
                         <select wire:model="transaction_status" id="transaction_status"
                             class="bg-gray-50 border border-gray-300 text-gray-900 font-semibold text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5">
                             <option value="">Select Lease Status</option>
                             <option value="pending">Pending</option>
                             <option value="confirmed">Confirmed</option>
                             <option value="ongoing">On-going</option>
                             <option value="done">Done</option>
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
                             class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-not-allowed">
                     </div>
                 </div>

                 <!-- Actions Buttons -->
                 <div class="flex justify-between items-center space-y-2 mt-6">
                     <x-ghost-button onclick="history.back()" type="button">
                         Cancel
                     </x-ghost-button>
                     <x-button wire:loading.attr="disabled" wire:target="image" wire:click="confirmEdit">
                        Save Changes
                     </x-button>
                 </div>
             </form>


             <!-- Edit Confirmation Modal -->
             <x-dialog-modal wire:model.live="confirmEditItem">
                 <x-slot name="title">
                     {{ __('Edit Lease') }}
                 </x-slot>

                 <x-slot name="content">
                     {{ __('Are you sure you want to save changes on this lease?') }}
                 </x-slot>

                 <x-slot name="footer">
                     <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                         {{ __('Cancel') }}
                     </x-secondary-button>

                     <x-button class="ms-3 bg-green text-white" wire:click="updateLease" wire:loading.attr="disabled">
                         {{ __('Edit Lease') }}
                     </x-button>
                 </x-slot>
             </x-dialog-modal>

         </div>
     </div>
 </div>
