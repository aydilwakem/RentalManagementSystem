<div class="min-h-[550px] container mx-auto p-6 ">
    <!-- Back Button -->
    <div class="mb-4">
        <button onclick="window.history.back();"
            class="inline-flex items-center text-gray-700 hover:text-gray-900 font-semibold focus:outline-none hover:underline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Back to Payment Methods
        </button>
    </div>
    @if ($deletedPayments->isEmpty())
    <!-- Empty Page Message -->
    <div class="text-center py-10">
        <p class="text-gray-500 text-lg font-semibold">No deleted payment methods yet.</p>
    </div>
    @else
    {{-- Display Session Message --}}
    @if (session('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
        {{ session('message') }}
    </div>
    @endif
    <div>
        <!-- Table -->
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($deletedPayments as $payment)
                <div class="bg-white rounded-lg shadow-md overflow-hidden w-full max-w-md mx-auto">
                    <a href="#">
                        <img class="w-full h-56 object-cover"
                            src="{{ asset('storage/' . $payment->mode_of_payment_qr_image) }}"
                            alt="{{ $payment->mode_of_payment_name }}" />
                    </a>
                    <div class="p-6 text-center">
                        <a href="#">
                            <h5 class="mb-3 text-2xl font-bold text-gray-900">
                                {{ $payment->mode_of_payment_name }}</h5>
                        </a>
                        <p class="text-gray-700">{{ $payment->account_name }}</p>
                        <p class="text-gray-700">{{ $payment->account_number }}</p>
                        <div class="mt-5 flex justify-center space-x-4">
                            <x-button wire:click="restorePayment({{ $payment->id }})">
                                Restore
                            </x-button>
                            <!-- Delete Forever Button -->
                            <x-button
                                class="!bg-red-500 hover:!bg-red-600 focus:outline-none focus:ring-2 focus:!ring-red-500 text-white font-semibold px-4 py-2 rounded"
                                wire:click="confirmDeleteForever({{ $payment->id }})">
                                Delete Forever
                            </x-button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Payment Method Forever') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to permanently delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deletePaymentForever({{ $payment->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Payment Method') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
    @endif
</div>