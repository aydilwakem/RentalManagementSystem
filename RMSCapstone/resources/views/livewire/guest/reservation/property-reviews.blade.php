<div x-data="{ open: @entangle('showReviewsModal') }">
    <!-- Star Rating Button -->
    @if ($totalReviews > 0)
        <button x-on:click="$wire.showReviews()"
            class="flex items-center space-x-1 text-yellow-500 hover:text-yellow-600 transition-colors  hover:underline"
            title="View {{ $totalReviews }} reviews">
            <!-- Star icons -->
            <div class="flex">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($averageRating))
                        <i class="fas fa-star text-yellow-400"></i>
                    @elseif ($i - 0.5 <= $averageRating)
                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                    @else
                        <i class="far fa-star text-yellow-400"></i>
                    @endif
                @endfor
            </div>

            <span class="text-sm text-gray-600 ml-1 group-hover:text-gray-800">
                {{ $averageRating }} ({{ $totalReviews }} review{{ $totalReviews > 1 ? 's' : '' }})
            </span>
        </button>
    @else
        <span class="text-sm text-gray-500 italic">(No reviews yet)</span>
    @endif

    <!-- Reviews Modal -->
    <div x-cloak x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-primary-800 text-white px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-semibold">Guest Reviews</h3>
                    <p class="text-sm opacity-90">{{ $propertyName }}</p>
                </div>
                <button x-on:click="$wire.closeReviews()" class="text-white hover:text-gray-200 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                <!-- Average Rating Summary -->
                @if ($totalReviews > 0)
                    <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="text-center flex-1">
                                <div class="text-5xl font-bold text-gray-800">{{ $averageRating }}/5</div>
                                <div class="flex justify-center mt-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star text-yellow-400 text-xl"></i>
                                        @elseif ($i - 0.5 <= $averageRating)
                                            <i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400 text-xl"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-sm text-gray-600 mt-2">{{ $totalReviews }}
                                    review{{ $totalReviews > 1 ? 's' : '' }}</div>
                            </div>

                            <!-- Rating Breakdown -->
                            <div class="space-y-2 flex-1">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm text-gray-600 w-4">{{ $i }}</span>
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        <div class="w-32 bg-gray-200 rounded-full h-2.5 flex-1">
                                            <div class="bg-yellow-400 h-2.5 rounded-full"
                                                style="width: {{ $totalReviews > 0 ? ($ratingBreakdown[$i] / $totalReviews) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-600 w-8 text-right">{{ $ratingBreakdown[$i] }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviews List -->
                <div class="space-y-6 border border-gray-300 rounded-md p-3">
                    @forelse ($propertyReviews as $review)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex space-x-3">
                                    <h4 class="font-semibold text-gray-800">{{ $review['guest_name'] }}</h4>
                                    <div class="flex items-center space-x-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review['overall_rating'])
                                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                                            @else
                                                <i class="far fa-star text-yellow-400 text-sm"></i>
                                            @endif
                                        @endfor
                                        <span
                                            class="text-sm text-gray-600 ml-1">{{ $review['overall_rating'] }}/5</span>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ $review['date_formatted'] }}
                                </span>
                            </div>

                            @if (!empty($review['comments']))
                                <p class="text-gray-700 mb-4 bg-gray-50 p-4 rounded-lg text-justify">
                                    {{ $review['comments'] }}</p>
                            @endif

                            <!-- Detailed Ratings -->
                            @if (!empty($review['ratings']) && count($review['ratings']) > 1)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    @foreach ($review['ratings'] as $rating)
                                        @if ($rating['type'] !== 'Overall Experience')
                                            <div class="flex items-center justify-between py-1">
                                                <span class="text-gray-600">{{ $rating['type'] }}:</span>
                                                <div class="flex items-center space-x-1">
                                                    <span
                                                        class="text-yellow-500 font-medium">{{ $rating['value'] }}/5</span>
                                                    <div class="flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $rating['value'])
                                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                            @else
                                                                <i class="far fa-star text-yellow-400 text-xs"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-comment-slash text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg">No reviews available for this property yet.</p>
                            <p class="text-sm mt-1">Be the first to share your experience!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Close Button at Bottom -->
                {{-- <div class="mt-6 text-center">
                    <x-button x-on:click="$wire.closeReviews()" class="!bg-green-700 hover:!bg-green-800">
                        Close Reviews
                    </x-button>
                </div> --}}
            </div>
        </div>
    </div>
</div>
