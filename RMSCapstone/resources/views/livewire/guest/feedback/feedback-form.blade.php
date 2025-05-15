<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-green-700 mb-2">We’d Love Your Feedback</h1>
            <p class="text-gray-600">
                Thank you for choosing <strong>Canopy Farm</strong> for your recent stay. Your feedback helps us improve
                and provide memorable experiences.<br>
                Please take a moment to share your thoughts with us!
            </p>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-4 bg-green-100 text-green-700 border border-green-200 rounded">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">
            {{-- Transaction ID --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                <input type="text" wire:model.defer="transactionId"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                @error('transactionId')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Rating Types --}}
            @foreach ($ratingTypes as $type)
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">{{ $type->rating_name }}</label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg wire:click="$set('ratingValues.{{ $type->id }}', {{ $i }})" xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7 cursor-pointer transition duration-150 ease-in-out hover:scale-110 {{ isset($ratingValues[$type->id]) && $ratingValues[$type->id] >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.204 3.713a1 1 0 00.95.69h3.905c.969 0 1.371 1.24.588 1.81l-3.158 2.295a1 1 0 00-.364 1.118l1.204 3.713c.3.921-.755 1.688-1.54 1.118L10 13.348l-3.158 2.295c-.784.57-1.838-.197-1.539-1.118l1.204-3.713a1 1 0 00-.364-1.118L3.985 9.14c-.783-.57-.38-1.81.588-1.81h3.905a1 1 0 00.95-.69l1.204-3.713z" />
                            </svg>
                        @endfor
                    </div>
                    @error("ratingValues.{$type->id}")
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            {{-- Comments --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Comments (Optional)</label>
                <textarea wire:model.defer="comments"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                    rows="4"></textarea>
            </div>

            {{-- Submit --}}
            <div class="text-right">
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>