<div class="min-h-screen bg-gradient-to-br from-green-50 to-white flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-3xl bg-white shadow-2xl rounded-2xl p-10 border border-gray-100">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-700">We’d Love Your Feedback</h1>
            <p class="mt-4 text-gray-600 leading-relaxed text-base">
                Thank you for choosing <strong>Canopy Farm</strong> for your recent stay!
                Your feedback helps us grow fresh ideas and cultivate better experiences for our guests.
                Please take a moment to share your thoughts with us!
            </p>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="p-4 mb-6 bg-green-100 text-green-800 border border-green-200 rounded-md text-sm font-medium">
                {{ session('message') }}
            </div>
        @endif

        <!-- Feedback Form -->
        <form wire:submit.prevent="submit" class="space-y-6">

            <!-- Transaction ID -->
            <div>
                <label class="block text-md font-semibold text-gray-700 mb-1">Reservation / Transaction ID</label>
                <input type="text" wire:model.defer="transactionId"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="e.g. TXN-2025-0101">
                @error('transactionId')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ratings -->
            @foreach ($ratingTypes as $type)
                <div>
                    <label class="block text-md font-semibold text-gray-800">{{ $type->rating_name }}</label>
                    <div class="flex items-center space-x-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span wire:click="$set('ratingValues.{{ $type->id }}', {{ $i }})"
                                class="cursor-pointer text-4xl transition transform duration-100 hover:scale-125 {{ isset($ratingValues[$type->id]) && $ratingValues[$type->id] >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                                ★
                            </span>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-500 mt-1 italic">Tap to rate from 1 (poor) to 5 (excellent)</p>
                    @error("ratingValues.{$type->id}")
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            <!-- Comments -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Additional Comments (Optional)</label>
                <textarea wire:model.defer="comments"
                    class="w-full border resize-none border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    rows="4" placeholder="Tell us what you loved or what could be improved..."></textarea>
                <p class="text-xs text-gray-400 mt-1">Your suggestions are valuable to us</p>
            </div>

            <!-- Submit -->
            <div class="text-right">
                <x-button type="submit">
                    <div class="flex items-center justify-center">
                        <!-- Spinner -->
                        <span wire:loading wire:target="submit" class="mr-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                </path>
                            </svg>
                        </span>

                        <i class="fas fa-check mr-2" wire:loading.remove wire:target="submit"></i>

                        <!-- Button Text -->
                        <span wire:loading.remove wire:target="submit">
                            Submit Feedback
                        </span>
                    </div>
                </x-button>
            </div>
        </form>
    </div>
</div>
