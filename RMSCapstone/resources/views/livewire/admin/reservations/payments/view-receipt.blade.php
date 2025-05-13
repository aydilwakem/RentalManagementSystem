<div class="p-6 bg-white rounded-lg shadow-md max-w-5xl mx-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Receipt Details') }}
        </h2>
    </x-slot>

    <!-- Back Button -->
    <div class="relative flex items-center mb-4">
        <p class="text-gray-700 mt-1">Review the payment information below.</p>
        <button onclick="history.back()"
            class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-9px]">
            <span class="leading-none translate-y-[-3px]">&times;</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Left Column: Screenshot -->
        <div class="rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-2">Screenshot Uploaded</h3>
                <div class="flex justify-center items-center">
                    <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot"
                        class="rounded object-cover h-auto w-full" style="max-height: none !important;" />
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Details -->
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Amount Paid:</div>
                    <div class="text-gray-900">
                        @if (in_array($payment->payment_status, ['completed', 'failed']))
                            {{ number_format($amount_paid, 2) }}
                        @else
                            <input type="number" step="0.01" wire:model.defer="amount_paid"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder="Enter amount" />
                        @endif
                    </div>
                </div>
                @error('amount_paid')
                    <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Payment Type:</div>
                    <div class="text-gray-900">
                        @if ($payment_type)
                            {{ $payment_type }}
                        @else
                            <select wire:model="payment_type"
                                class="form-select mt-1 block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                <option value="Room Rent">Room Rent</option>
                                <option value="House Rent">House Rentoption>
                                <option value="Activity Fee">Activity Fee</option>
                                <option value="Event Hall">Event Hall</option>
                                <option value="Event Package">Event Packag</option>
                                <option value="Security Deposit">Security Deposit</option>
                            </select>
                        @endif
                    </div>
                </div>
                @error('payment_type')
                    <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Mode of Payment:</div>
                    <div class="text-gray-900">{{ $payment->paymentMethod->mode_of_payment_name }}</div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Payment Reference No.:</div>
                    <div class="text-gray-900">#{{ $payment->payment_reference_number }}</div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Payment Upload Date:</div>
                    <div class="text-gray-900">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y g:i A') }}</div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div class="flex justify-between items-center">
                    <div class="font-medium text-gray-700">Status:</div>
                    <div class="text-gray-900">{{ ucfirst($payment->payment_status) }}</div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-md p-4">
                <div>
                    <div class="font-medium text-gray-700">Notes:</div>
                    <div class="text-gray-900 whitespace-pre-wrap">{{ $payment->notes ?? 'No note added' }}</div>
                </div>
            </div>

            <div class="flex justify-between space-x-2 mt-6">
                {{-- <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-gray-700 hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-500 focus:!outline-none">
                    Cancel
                </x-button> --}}

                @if ($payment->payment_status !== 'completed' && $payment->payment_status !== 'failed')
                    <x-button wire:loading.attr="disabled" wire:click="$set('showRejectModal', true)"
                        class="bg-red-600 hover:bg-red-700 text-white">
                        Reject
                    </x-button>
                    <x-button wire:loading.attr="disabled" wire:click="ConfirmReceiptModal"
                        class="bg-green-700 hover:bg-green-800 text-white">
                        Confirm
                    </x-button>
                @elseif ($payment->payment_status === 'completed')
                    <div class="bg-green-200 mx-auto rounded-md p-3">
                        <div class="flex items-center justify-center">
                            <div class="text-green-600 font-semibold text-lg">Receipt Confirmed</div>
                        </div>
                    </div>
                @elseif ($payment->payment_status === 'failed')
                    <div>
                        <span class="text-red-500 font-medium">Receipt Rejected</span>
                        @if ($payment->rejection_reason)
                            <p class="text-sm text-gray-700 mt-2">Reason:{{ $payment->rejection_reason }}
                            </p>
                        @else
                            <p class="text-sm text-gray-700 mt-2">No reason provided. </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="confirmReceiptItem">
        <x-slot name="title">
            {{ __('Confirm Receipt') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to confirm this receipt?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmReceiptItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green-500 hover:bg-green-700 text-white" wire:click="confirmReceipt"
                wire:loading.attr="disabled">
                {{ __('Confirm') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model.live="rejectReceiptItem">
        <x-slot name="title">
            {{ __('Reject Receipt') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to reject this receipt?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('rejectReceiptItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-red-500 hover:bg-red-700 text-white" wire:click="rejectReceipt"
                wire:loading.attr="disabled">
                {{ __('Reject') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>


    @if ($showRejectModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Reject Payment</h2>

                <div class="mb-4">
                    <label for="rejection_reason" class="block font-medium text-gray-700">Rejection Reason</label>
                    <select wire:model="rejection_reason" id="rejection_reason"
                        class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">-- Select Reason --</option>
                        <option value="Incomplete details">Incomplete details</option>
                        <option value="Invalid receipt">Invalid receipt</option>
                        <option value="Mismatched amount">Mismatched amount</option>
                        <option value="Duplicate payment">Duplicate payment</option>
                        <option value="Suspicious activity">Suspicious activity</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <x-button wire:click="rejectReceipt" wire:loading.attr="disabled"
                        class="bg-red-500 hover:bg-red-700 text-white">
                        {{ __('Confirm Rejection') }}
                    </x-button>
                    <x-button wire:click="$set('showRejectModal', false)"
                        class="bg-gray-300 text-gray-700 hover:bg-gray-400">
                        {{ __('Cancel') }}
                    </x-button>
                </div>
            </div>
        </div>
    @endif
</div>
