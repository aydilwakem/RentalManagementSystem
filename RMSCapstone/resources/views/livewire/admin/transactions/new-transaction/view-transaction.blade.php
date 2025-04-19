<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Transaction') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto w-full border rounded-lg bg-white shadow-md">

        <!-- Back Button -->
        <div class="mx-auto w-full lg:py-2 flex justify-end items-center">
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <!-- transactions ID -->
        <h2 class="mb-2 text-xl text-center font-semibold leading-none text-gray-900 md:text-2xl">Transaction ID:
            {{ $transactions->id }}
        </h2>

        <!-- Guest Information Table -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Reservation Holder</h3>

            <div class="relative overflow-x-auto mt-4">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 border border-gray-200">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Contact Number</th>
                            <th scope="col" class="px-6 py-3">Address</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4">
                                {{ $transactions->first_name }} {{ $transactions->middle_name }}
                                {{ $transactions->last_name }} {{ $transactions->suffix }}
                            </td>
                            <td class="px-6 py-4">{{ $transactions->email }}</td>
                            <td class="px-6 py-4">{{ $transactions->contact_number }}</td>
                            <td class="px-6 py-4"> {{ $transactions->city_municipality }}, {{ $transactions->country }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Transaction Details Table -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Reservation Details</h3>

            <div class="relative overflow-x-auto mt-4">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 border border-gray-200">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Room</th>
                            <th scope="col" class="px-6 py-3">Activity</th>
                            <th scope="col" class="px-6 py-3">Check-in Date</th>
                            <th scope="col" class="px-6 py-3">Check-in Time</th>
                            <th scope="col" class="px-6 py-3">Check-out Date</th>
                            <th scope="col" class="px-6 py-3">Check-out Time</th>
                            <th scope="col" class="px-6 py-3">Adults</th>
                            <th scope="col" class="px-6 py-3">Kids</th>
                            <th scope="col" class="px-6 py-3">Total Pax</th>
                            <th scope="col" class="px-6 py-3">Pets</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4 text-gray-800">
                                {{ $transactions->property->name_number ?? 'No Room Assigned' }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $transactions->activity->name ?? 'No Activity' }}
                            </td>
                            <td class="px-4 py-3 text-gray-800">
                                {{ \Carbon\Carbon::parse($transaction->check_in_date)->format('F j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">
                                {{ \Carbon\Carbon::parse($transactions->check_in_time)->format('h:i A') }}
                            </td>
                            <td class="px-4 py-3 text-gray-800">
                                {{ \Carbon\Carbon::parse($transaction->check_out_date)->format('F j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">
                                {{ \Carbon\Carbon::parse($transactions->check_out_time)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $transactions->total_adults }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $transactions->total_kids }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $transactions->pax }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $transactions->pets }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Payment Information Table -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>

            <div class="relative overflow-x-auto mt-4">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 border border-gray-200">
                    <tbody>
                        <tr class="bg-white border-b border-gray-200">
                            <th class="px-6 py-4 text-gray-700 bg-gray-50">Payment Method</th>
                            <td class="px-6 py-4 text-gray-800">
                                {{ $transactions->paymentMethod->mode_of_payment_name ?? 'Not Provided' }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th class="px-6 py-4 text-gray-700 bg-gray-50">Reference Number</th>
                            <td class="px-6 py-4 text-gray-800">
                                {{ $transactions->payment_reference_number ?? 'Not Provided' }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th class="px-6 py-4 text-gray-700 bg-gray-50">Payment Screenshot</th>
                            <td class="px-6 py-4 text-left">
                                <span
                                    class="cursor-pointer font-semibold
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           {{ $transaction->isPaid ? 'text-green-600' : 'text-yellow-500' }}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            hover:underline"
                                    wire:click="confirmReceipt({{ $transaction->id }})" wire:loading.attr="disabled">
                                    {{ $transaction->isPaid ? 'View Screenshot' : 'Confirm Receipt' }}
                                </span>
                            </td>
                        </tr>

                        <tr class="bg-white border-b border-gray-200">
                            <th class="px-6 py-4 text-gray-700 bg-gray-50">Total Amount</th>
                            <td class="px-6 py-4 text-gray-800">₱{{ number_format($transactions->total_amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



        <!-- Action Buttons -->
        <div class="flex items-center justify-between space-x-4 mt-3 mb-3">

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-new-transaction', $transactions->id) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="confirmDelete({{ $transactions->id }})">
                Delete
            </x-button>

        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete">
            <x-slot name="title">
                {{ __('Delete Transaction') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this transaction?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteTransaction({{ $transactions->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Transaction') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
        <!-- End of Delete Confirmation Modal -->

        <!-- Receipt Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemReceipt">
            <x-slot name="title">
                {{ __('Confirm Receipt') }}
            </x-slot>

            <x-slot name="content">
                @if ($selectedTransaction)
                    <!-- Payment Screenshot at the Top -->
                    <div class="flex flex-col items-center">
                        <img src="{{ asset($selectedTransaction->payment_screenshot ? 'storage/' . $selectedTransaction->payment_screenshot : 'images/rms-default.png') }}"
                            alt="Payment Screenshot" class="w-64 h-auto mb-4">
                    </div>

                    <!-- Payment Details Below -->
                    <div class="text-left">
                        <p class="text-lg font-semibold">Name: {{ $selectedTransaction->first_name ?? 'N/A' }}
                        </p>
                        <p class="text-lg font-semibold">Payment Method:
                            {{ $selectedTransaction->paymentMethod->mode_of_payment_name ?? 'N/A' }}
                        </p>
                        <p class="text-lg font-semibold">Payment Reference:
                            {{ $selectedTransaction->payment_reference_number ?? 'N/A' }}
                        </p>
                    </div>
                @else
                    {{ __('No payment screenshot available.') }}
                @endif
            </x-slot>
            <p></p>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemReceipt', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="confirmPaymentReceipt({{ $transaction?->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Confirm Receipt') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
        <!-- End of Receipt Confirmation Modal -->

    </div>
</div>