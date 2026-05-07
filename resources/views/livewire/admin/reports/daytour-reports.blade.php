<div>
    {{-- Table --}}
    <div class="overflow-x-auto p-3 mb-3">
        <div class="flex items-center justify-between p-4">
            <div class="flex gap-4 w-full">
                <div class="relative w-full">
                    {{-- Start Date --}}
                    <div class="w-full">
                        <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Start Date</label>
                        <input type="date" wire:model.lazy="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- End Date --}}
                <div class="w-full">
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">End
                        Date
                    </label>
                    <input type="date" wire:model.lazy="end_date" id="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('end_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Filter -->
                <div class="w-full">
                    <label for="daytour_status"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Event
                        Status:</label>
                    <select id="daytour_status" name="daytour_status" wire:model="daytourStatusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
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

                {{-- Apply Filter --}}
                <div class="flex items-end">
                    <x-button icon="fa fa-filter" wire:click="applyDaytourFilter">
                        Apply Filter
                    </x-button>
                </div>
            </div>
        </div>

        @if (!$filterApplied)
        <div class="w-full text-center py-4">
            <span class="text-green-500 font-medium dark:text-green-300">
                Please apply filters first to generate the daytour bookings summary.
            </span>
        </div>
        @else
        @if (!empty($filteredTransactions))
        {{-- EXPORT PDF BUTTON --}}
        <div class="flex justify-end mb-2 space-x-2 mr-4">
            {{-- EXPORT PDF BUTTON --}}
            <div class="relative">
                <x-button icon="fa-solid fa-file" wire:click="exportDaytourSummary" wire:loading.attr="disabled">
                    Export PDF
                </x-button>

                <div wire:loading wire:target="exportDaytourSummary"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-2 pt-1">
                    <span class="text-sm text-green-700 font-semibold">Exporting PDF...</span>
                    <i class="fas fa-spinner fa-spin text-green-700 text-md"></i>
                </div>
            </div>

            {{-- EXPORT CSV BUTTON --}}
            {{-- <div class="relative">
                <x-warning-button icon="fa-solid fa-file" wire:click="exportEventCsv" wire:loading.attr="disabled">
                    Export CSV
                </x-warning-button>

                <div wire:loading wire:target="exportEventCsv"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-4">
                    <span class="text-sm text-yellow-700 font-semibold">Exporting CSV...</span>
                </div>
            </div> --}}

            {{-- Export Excel --}}
            <div class="relative">
                <x-warning-button icon="fa-solid fa-file" wire:click="exportDaytourExcel" wire:loading.attr="disabled">
                    Export Excel
                </x-warning-button>

                <div wire:loading wire:target="exportDaytourExcel"
                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded pl-2 pt-1">
                    <span class="text-sm text-yellow-700 font-semibold">Exporting Excel...</span>
                    <i class="fas fa-spinner fa-spin text-yellow-700 text-md"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    {{-- Start of Column Headers --}}
                    <thead
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                                <button class="flex items-center">
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
                                <button class="flex items-center">
                                    Guest Name
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


                            <th scope="col" class="px-4 py-3">Tour Package</th>
                            <th scope="col" class="px-4 py-3">Tour Date</th>

                            {{-- Pax --}}
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('pax')">
                                <button class="flex items-center">
                                    Pax
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
                            {{-- Amount --}}
                            <th scope="col" class="px-4 py-3">Amount</th>
                            {{-- Status --}}
                            <th scope="col" class="px-4 py-3">Status</th>

                        </tr>
                    </thead>
                    {{-- End of Column Headers --}}

                    {{-- Start of Table Body --}}
                    <tbody class="text-left dark:bg-gray-700">
                        @forelse ($filteredTransactions as $transaction)
                        <tr
                            class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">

                            {{-- ID --}}
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200">
                                {{ $transaction->transaction_number ?? 'N/A' }}
                            </th>

                            {{-- First Name and Last Name --}}
                            <th scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200">
                                {{ $transaction->transactionUser->first_name }}
                                {{ $transaction->transactionUser->last_name }}
                            </th>

                            {{-- Tour Package --}}
                            <td class="px-4 py-3">
                                {{ $transaction->dayTour->name ?? 'N/A' }}
                            </td>

                            {{-- Tour Date --}}
                            <td>{{ $transaction->start_datetime ?? 'N/A' }}</td>

                            {{-- Pax --}}
                            <td class="px-4 py-3"> {{ $transaction->pax }}</td>

                            {{-- Total Amount --}}
                            <td class="px-4 py-3">
                                ₱{{ number_format($transaction->sub_total, 2) }}
                            </td>

                            {{-- Transaction Status --}}
                            <td class=" px-4 py-2">
                                @if ($transaction->transaction_status === 'pending')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting
                                    Payment</span>
                                @elseif ($transaction->transaction_status === 'reserved')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending
                                    Verification</span>
                                @elseif ($transaction->transaction_status === 'receipt_verified')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment
                                    Verified</span>
                                @elseif ($transaction->transaction_status === 'confirmed')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                                @elseif ($transaction->transaction_status === 'ongoing')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going
                                </span>
                                @elseif ($transaction->transaction_status === 'done')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-600">Completed</span>
                                @elseif ($transaction->transaction_status === 'no_show')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500">No
                                    Show</span>
                                @elseif ($transaction->transaction_status === 'terminated')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated</span>
                                @elseif ($transaction->transaction_status === 'expired')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500">Expired</span>
                                @elseif ($transaction->transaction_status === 'cancelled')
                                <span
                                    class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Cancelled</span>
                                @endif
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="15" class="text-center py-10 text-green-700 font-semibold">
                                No daytours found for this date range.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    {{-- End of Table Body --}}
                </table>
            </div>

        </div>
        @endif
        @endif
    </div>
</div>
