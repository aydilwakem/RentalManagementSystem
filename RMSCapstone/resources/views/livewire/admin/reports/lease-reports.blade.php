<div>
    {{-- Table --}}
    <div class="overflow-x-auto p-3 mb-3">
        <div class="flex items-center justify-between p-4">
            <div class="flex gap-4 w-full">
                <div class="relative w-full">
                    {{-- Start Date --}}
                    <div class="w-full">
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">
                            Start Date</label>
                        <input type="date" wire:model.lazy="start_date" id="start_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- End Date --}}
                <div class="w-full">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">End Date
                    </label>
                    <input type="date" wire:model.lazy="end_date" id="end_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('end_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Select A Property --}}
                <div class="w-full">
                    <label for="select_property" class="block mb-2 text-sm font-medium text-gray-900">Select a Property
                    </label>
                    <select wire:model.live="propertyFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">All Properties</option>
                        @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name_number }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-full">
                    <label for="property_status" class="block mb-2 text-sm font-medium text-gray-900">Lease
                        Status:</label>
                    <select id="property_status" name="event_status" wire:model.live="propertyStatusFilter"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">All</option>
                        <option value="pending">Awaiting Payment</option>
                        <option value="reserved">Pending Verification</option>
                        <option value="receipt_verified">Payment Verified</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="ongoing">On-going</option>
                        <option value="done">Completed</option>
                        <option value="no_show">No Show</option>
                        <option value="terminated">Terminated</option>
                        <option value="expired">Expired</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                {{-- Apply Filter Button --}}
                <div class="flex items-end">
                    <x-button icon="fa fa-filter" wire:click="applyLeaseFilter">
                        Apply Filter
                    </x-button>
                </div>
            </div>
        </div>

        @if (!$filterApplied)
        <div class="w-full text-center py-4">
            <span class="text-green-500 font-medium">
                Please apply filters first to generate the leases summary.
            </span>
        </div>
        @else

        @if (!empty($filteredTransactions))
        {{-- EXPORT PDF BUTTON --}}
        <div class="flex justify-end mb-2 space-x-2 mr-4">
            <div class="relative">
                <x-button icon="fa-solid fa-file" wire:click="exportLeaseSummary" wire:loading.attr="disabled">
                    Export PDF
                </x-button>

                <div wire:loading wire:target="exportLeaseSummary"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-green-700 font-semibold pt-3">Exporting PDF...</span>
                </div>
            </div>

            {{-- EXPORT CSV BUTTON --}}
            <div class="relative">
                <x-warning-button icon="fa-solid fa-file" wire:click="exportLeaseCsv" wire:loading.attr="disabled">
                    Export CSV
                </x-warning-button>

                <div wire:loading wire:target="exportLeaseCsv"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-yellow-700 font-semibold">Exporting CSV...</span>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
            <table class="w-full rounded-md text-left">
                {{-- Start of Column Headers --}}
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                            <button class="flex">
                                ID
                                @if ($sortBy !== 'id')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                {{-- Up arrow (Ascending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                {{-- Down arrow (Descending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('first_name')">
                            <button class="flex">
                                Tenant
                                @if ($sortBy !== 'first_name')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                {{-- Up arrow (Ascending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                {{-- Down arrow (Descending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>


                        <th scope="col" class="px-4 py-3">Property Rented</th>

                        {{-- Pax --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('pax')">
                            <button class="flex">
                                Total Tenants
                                @if ($sortBy !== 'pax')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                {{-- Up arrow (Ascending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                {{-- Down arrow (Descending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Monthly Rent --}}
                        <th scope="col" class="px-4 py-3">Monthly Rent</th>

                        {{-- Event Start --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('start_datetime')">
                            <button class="flex">
                                Lease Start Date
                                @if ($sortBy !== 'start_datetime')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                {{-- Up arrow (Ascending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                {{-- Down arrow (Descending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        {{-- Event End --}}
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('end_datetime')">
                            <button class="flex">
                                Lease End Date
                                @if ($sortBy !== 'end_datetime')
                                {{-- Default icon when sorting is not active --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                {{-- Up arrow (Ascending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                {{-- Down arrow (Descending) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>
                        {{-- Deposit Amount --}}
                        <th scope="col" class="px-4 py-3">Deposit Amount</th>
                        {{-- Total Amount --}}
                        <th scope="col" class="px-4 py-3">Total Rent</th>
                        {{-- Status --}}
                        <th scope="col" class="px-4 py-3">Status</th>

                    </tr>
                </thead>
                {{-- End of Column Headers --}}

                {{-- Start of Table Body --}}
                <tbody class="text-left ">
                    @forelse ($filteredTransactions as $transaction)
                    <tr class="border-b hover:bg-gray-50">

                        {{-- ID --}}
                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $fakeIDs[$transaction->id] ?? 'LSE-' . str_pad($loop->index + 1, 3, '0', STR_PAD_LEFT) }}
                        </th>

                        {{-- First Name and Last Name --}}
                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $transaction->transactionUser->first_name }}
                            {{ $transaction->transactionUser->last_name }}
                        </th>

                        {{-- Property --}}
                        <td class="px-4 py-3">
                            @foreach ($transaction->properties as $property)
                            {{ $property->name_number ?? 'N/A' }}<br>
                            @endforeach
                        </td>

                        {{-- Pax --}}
                        <td class="px-4 py-3"> {{ $transaction->pax }}</td>

                        <td class="px-4 py-3">₱ @foreach ($transaction->properties as $property)
                            {{ number_format($property->amount ?? 'N/A', 2) }}<br>
                            @endforeach
                        </td>

                        {{-- Lease Start Date --}}
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                        </td>

                        {{-- Lease End Date --}}
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}
                        </td>

                        {{-- Total Amount --}}
                        <td class="px-4 py-3">
                            ₱{{ number_format($transaction->deposit_amount, 2) }}
                        </td>

                        {{-- Total Amount --}}
                        <td class="px-4 py-3">
                            ₱{{ number_format($transaction->total_amount, 2) }}
                        </td>

                        {{-- Transaction Status --}}
                        <td class=" px-4 py-2">
                            @if ($transaction->transaction_status === 'pending')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Awaiting
                                Payment</span>
                            @elseif ($transaction->transaction_status === 'confirmed')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                            @elseif ($transaction->transaction_status === 'ongoing')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-600">On-Going
                            </span>
                            @elseif ($transaction->transaction_status === 'done')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                            @elseif ($transaction->transaction_status === 'terminated')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">Terminated</span>
                            @endif
                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="15" class="text-center py-10 text-green-700 font-semibold">
                            No leases found for this date range.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                {{-- End of Table Body --}}
                @endif
            </table>
        </div>

        @endif

        {{-- Pagination --}}
        <div class="py-4 px-3">
            <div class="flex ">
                <div class="flex space-x-4 items-center mb-3">
                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                    <select wire:model.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $transactions->links() }}
        </div>
    </div>


</div>