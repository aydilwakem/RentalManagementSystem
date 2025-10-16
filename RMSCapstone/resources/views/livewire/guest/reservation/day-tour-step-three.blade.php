<!-- day-tour-step-three.blade.php -->
<div class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800">Review Your Booking</h2>

    <!-- Tour Details -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">Tour Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Tour:</strong> {{ $selectedTour->name }}</p>
                <p><strong>Rate:</strong> {{ $selectedRate->rate_name }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($tourDate)->format('M d, Y') }}</p>
            </div>
            <div>
                <p><strong>Adults:</strong> {{ $adultCount }} x ₱{{ number_format($selectedRate->adult_rate, 2) }}</p>
                @if($kidCount > 0)
                    <p><strong>Children:</strong> {{ $kidCount }} x ₱{{ number_format($selectedRate->kid_rate, 2) }}</p>
                @endif
                <p><strong>Total Guests:</strong> {{ $totalGuests }}</p>
            </div>
        </div>
    </div>

    <!-- Price Summary -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">Price Summary</h3>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span>Subtotal:</span>
                <span>₱{{ number_format($this->subtotal, 2) }}</span>
            </div>

            <!-- Convenience Fee (Always shown) -->
            <div class="flex justify-between">
                <div class="flex items-center gap-2">
                    <span>Convenience Fee (3%):</span>
                    <div class="relative group inline-block">
                        <i class="fas fa-info-circle text-gray-500 text-xs cursor-pointer"></i>
                        <!-- Tooltip -->
                        <div
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-max max-w-xs text-xs text-white bg-gray-800 rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                            A processing fee is applied for secure online payments.
                        </div>
                    </div>
                </div>
                <span>₱{{ number_format($this->convenience_fee, 2) }}</span>
            </div>

            <hr>
            <div class="flex justify-between text-lg font-bold">
                <span>Total Amount:</span>
                <span class="text-green-600">₱{{ number_format($this->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Terms and Payment -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">Terms & Conditions</h3>
        <div class="prose max-w-none mb-4">
            {!! $terms_and_conditions !!}
        </div>

        <label class="flex items-center">
            <input type="checkbox" wire:model="terms" class="mr-2">
            <span>I agree to the terms and conditions</span>
        </label>
        @error('terms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <div class="mt-4">
            <button wire:click="register" wire:loading.attr="disabled"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold">
                <div wire:loading wire:target="register">
                    Processing...
                </div>
                <div wire:loading.remove wire:target="register">
                    Complete Reservation & Pay
                </div>
            </button>
        </div>
    </div>
</div>