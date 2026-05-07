<div class="min-h-screen bg-yellow-50 flex items-center justify-center px-4 py-12 font-sans">

    <div class="w-full max-w-2xl bg-white shadow-2xl rounded-3xl overflow-hidden border border-white/60 relative">

        <div class="h-2 bg-green-700"></div>

        <div class="p-8 md:p-10">

            <!-- Header Section -->
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full mb-6 shadow-sm">
                    <i class="fa-solid fa-heart text-3xl animate-pulse"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight mb-3">
                    How was your stay?
                </h1>
                <p class="text-gray-600 text-md leading-relaxed  mx-auto">
                    Thank you for choosing <strong>Canopy Farm</strong> for your recent stay! <br>
                    Your feedback helps us grow. Please take a moment to share your experience.
                </p>
            </div>

            <!-- Flash Message -->
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-transition
                    class="mb-8 p-4 bg-green-50 text-green-800 border border-green-200 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                        <span class="font-medium">{{ session('message') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-700 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Feedback Form -->
            <form wire:submit.prevent="submit" class="space-y-8">

                <!-- Transaction ID -->
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Reservation /
                        Transaction No.</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-receipt text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                        </div>
                        <input type="text" wire:model.defer="transaction_number"
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 font-medium focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="Ex. TXN-123ABCD">
                    </div>
                    @error('transaction_number')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1 font-medium">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Ratings Section -->
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Rate your
                        experience</label>

                    @foreach ($ratingTypes as $type)
                        <div
                            class="bg-gray-50 p-5 rounded-2xl border border-gray-100 hover:border-green-300 hover:bg-green-50/30 transition-colors duration-300">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <label class="text-md font-bold text-gray-800">{{ $type->rating_name }}</label>

                                <!-- Star Interaction -->
                                <div class="flex items-center gap-2" x-data="{ hoverRating: 0 }">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                            wire:click="$set('ratingValues.{{ $type->id }}', {{ $i }})"
                                            @mouseenter="hoverRating = {{ $i }}"
                                            @mouseleave="hoverRating = 0"
                                            class="focus:outline-none transition-transform duration-200 hover:scale-110 active:scale-90 p-1"
                                            title="{{ $i }} Star">

                                            <i class="fas fa-star text-2xl transition-colors duration-200"
                                                :class="{
                                                    'text-yellow-400 drop-shadow-sm': hoverRating >=
                                                        {{ $i }} || (!hoverRating &&
                                                            {{ $ratingValues[$type->id] ?? 0 }} >= {{ $i }}),
                                                    'text-gray-300': hoverRating < {{ $i }} && (
                                                        hoverRating || {{ $ratingValues[$type->id] ?? 0 }} <
                                                        {{ $i }})
                                                }">
                                            </i>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                            @error("ratingValues.{$type->id}")
                                <span
                                    class="text-red-500 text-xs mt-2 block font-semibold text-right">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <!-- Comments -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Additional
                        Comments</label>
                    <textarea wire:model.defer="comments"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-5 py-4 h-32 focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none text-gray-700 placeholder-gray-400"
                        placeholder="Tell us what you loved or how we can improve..."></textarea>
                    @error('comments')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-green-900/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3 group">

                        <span wire:loading.remove wire:target="submit" class="flex items-center gap-2 text-md">
                            Submit Feedback
                            <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                        </span>

                        <!-- Loading indicator -->
                        <span wire:loading wire:target="submit" class="flex items-center gap-2 text-md">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z"></path>
                            </svg>
                        </span>
                    </x-button>
                </div>

            </form>
        </div>
    </div>
</div>
