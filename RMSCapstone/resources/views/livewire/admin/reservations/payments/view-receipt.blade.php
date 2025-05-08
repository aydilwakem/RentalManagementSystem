<div class="p-6 bg-white rounded-lg shadow-md max-w-5xl mx-auto">
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            {{ __('View Receipt') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column: Screenshot -->
        <div class="flex justify-center items-start">
            <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot"
                class="rounded border border-gray-300 shadow max-w-full h-auto object-contain" />
        </div>

        <!-- Right Column: Payment Details -->
        <div class="space-y-4">

            <div class="flex justify-between">
                <label class="font-medium text-gray-700">Amount Paid:</label>

                @if(in_array($payment->payment_status, ['completed', 'failed']))
                    <!-- Check if payment status is completed or failed -->
                    <span class="text-gray-900">{{ number_format($amount_paid, 2) }}</span>
                    <!-- Display amount paid as text -->
                @else
                    <input type="number" step="0.01" wire:model.defer="amount_paid"
                        class="mt-1 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter amount" />
                @endif
            </div>

            @error('amount_paid')
                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror


            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Payment Type:</span>

                @if($payment_type)
                    <span class="text-gray-900">{{ $payment_type }}</span>
                @else
                    <select wire:model="payment_type"
                        class="form-select mt-1 block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Room Rent">Room Rent</option>
                        <option value="House Rent">House Rent</option>
                        <option value="Activity Fee">Activity Fee</option>
                        <option value="Event Hall">Event Hall</option>
                        <option value="Event Package">Event Package</option>
                        <option value="Security Deposit">Security Deposit</option>
                    </select>
                @endif
            </div>

            @error('payment_type')
                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror



            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Mode of Payment:</span>
                <span class="text-gray-900">{{ $payment->paymentMethod->mode_of_payment_name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Payment Reference No.:</span>
                <span class="text-gray-900">#{{ $payment->payment_reference_number }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Payment Upload Date:</span>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y g:i A')
                    }}</span>
            </div>


            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Status:</span>
                <span class="text-gray-900">{{ ucfirst($payment->payment_status) }}</span>
            </div>

            <div>
                <span class="font-medium text-gray-700">Notes:</span>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $payment->notes }}</p>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Payment Upload Date:</span>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($payment->verified_at)->format('F j, Y g:i A')
                    }}</span>
            </div>


            <!-- Confirm Receipt Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>

                @if ($payment->payment_status !== 'completed' && $payment->payment_status !== 'failed')
                    <x-button wire:loading.attr="disabled" wire:click="ConfirmReceiptModal">
                        Confirm Receipt
                    </x-button>
                    <x-button wire:loading.attr="disabled" wire:click="$set('showRejectModal', true)">
                        Reject
                    </x-button>

                @elseif ($payment->payment_status === 'completed')
                    <span class="text-green-500 font-medium">Receipt Confirmed</span>
                @elseif ($payment->payment_status === 'failed')
                    <div>
                        <span class="text-red-500 font-medium">Receipt Rejected</span>
                        @if($payment->rejection_reason)
                            <p class="text-sm text-gray-700 mt-2">Reason: {{ $payment->rejection_reason }}</p>
                        @else
                            <p class="text-sm text-gray-700 mt-2">No reason provided.</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Confirm Receipt Modal -->
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

                    <x-button class="ms-3 bg-green text-white" wire:click="confirmReceipt" wire:loading.attr="disabled">
                        {{ __('Confirm Receipt') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>

            <!-- Reject Receipt Modal -->
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

                    <x-button class="ms-3 bg-green text-white" wire:click="rejectReceipt" wire:loading.attr="disabled">
                        {{ __('Reject Receipt') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>


            @if($showRejectModal)
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                        <h2 class="text-lg font-semibold mb-4">Reject Payment</h2>

                        <div class="mb-4">
                            <label class="block font-medium text-gray-700">Rejection Reason</label>
                            <select wire:model="rejection_reason" class="form-select mt-1 block w-full">
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
                            <x-button wire:click="rejectReceipt" wire:loading.attr="disabled">
                                Confirm Rejection
                            </x-button>
                            <x-button wire:click="$set('showRejectModal', false)" class="bg-gray-300 text-black">
                                Cancel
                            </x-button>
                        </div>
                    </div>
                </div>
            @endif



        </div>
    </div>
</div>