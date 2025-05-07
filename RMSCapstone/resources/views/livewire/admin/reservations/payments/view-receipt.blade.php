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

            <div class="flex flex-col">
                <label class="font-medium text-gray-700">Amount Paid:</label>
                <input type="number" step="0.01" wire:model.defer="amount_paid"
                    class="mt-1 border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter amount" {{ in_array($payment->payment_status, ['completed', 'failed']) ?
                'disabled' : '' }} />
            </div>

            @error('amount_paid')
            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
            @enderror


            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Mode of Payment:</span>
                <span class="text-gray-900">{{ $payment->paymentMethod->mode_of_payment_name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Payment Reference No.:</span>
                <span class="text-gray-900">{{ $payment->payment_reference_number }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Upload Date:</span>
                <span class="text-gray-900">{{ $payment->payment_date }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Status:</span>
                <span class="text-gray-900">{{ $payment->payment_status }}</span>
            </div>

            <div>
                <span class="font-medium text-gray-700">Notes:</span>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $payment->notes }}</p>
            </div>

            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Verified At:</span>
                <span class="text-gray-900">{{ $payment->verified_at ?? 'Not yet verified' }}</span>
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
                <x-button wire:loading.attr="disabled" wire:click="RejectReceiptModal">
                    Reject
                </x-button>
                @elseif ($payment->payment_status === 'completed')
                <span class="text-green-500 font-medium">Receipt Confirmed</span>
                @elseif ($payment->payment_status === 'failed')
                <span class="text-red-500 font-medium">Receipt Rejected</span>
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


        </div>
    </div>
</div>