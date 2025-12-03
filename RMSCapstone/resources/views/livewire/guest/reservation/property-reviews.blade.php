<div x-data="{ open: @entangle('showReviewsModal') }">
    <!-- Room Rating -->
    @if ($totalReviews > 0)
        <button x-on:click="$wire.showReviews()"
            class="flex items-center space-x-1 text-gray-700 hover:text-yellow-600 transition-colors hover:underline"
            title="View {{ $totalReviews }} reviews">
            <div class="flex text-yellow-500">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($averageRating))
                        <i class="fas fa-star"></i>
                    @elseif ($i - 0.5 <= $averageRating)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star text-gray-300"></i>
                    @endif
                @endfor
            </div>

            <span class="text-sm text-gray-600 ml-1 group-hover:text-gray-800 font-medium">
                ({{ $totalReviews }} review{{ $totalReviews > 1 ? 's' : '' }})
            </span>
        </button>
    @else
        <span class="text-sm text-gray-500 italic">(No reviews yet)</span>
    @endif

    <!-- Review Modal BOdy -->
    <div x-cloak x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-start justify-center z-50 p-4 sm:p-8">

        <!-- Header -->
        <div
            class="bg-white rounded-xl shadow-2xl max-w-5xl w-full my-8 max-h-[90vh] overflow-hidden transform transition-all">
            <div class="relative bg-green-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-green-700">Guest Reviews for {{ $propertyName }}</h3>
                <button x-on:click="$wire.closeReviews()"
                    class="text-gray-400 hover:text-red-400 transition-colors absolute top-2 right-4 p-2 rounded-full hover:bg-gray-100"
                    title="Close">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Left Section: Overall Ratings -->
            <div class="flex p-6 max-h-[calc(90vh-80px)] overflow-y-auto">
                @if ($totalReviews > 0)
                    <div class="w-full lg:w-1/3 pr-6 border-r border-gray-200 sticky top-0">
                        <div class="mb-8 text-center">
                            <div class="flex items-baseline mb-2 justify-center">
                                <span class="text-6xl font-extrabold text-gray-900 leading-none mr-2">
                                    {{ $averageRating }}
                                </span>
                                <span class="text-3xl font-medium text-gray-500 leading-none">
                                    <i class="fas fa-star text-yellow-500"></i>
                                </span>
                            </div>
                            <p class="text-lg text-gray-600 font-medium">
                                {{ $totalReviews }} Guest Reviews
                            </p>
                        </div>

                        <!-- Rating Breakdown -->
                        <div class="space-y-3 mb-8">
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="flex items-center">
                                    <div
                                        class="flex items-center justify-center h-8 px-3 mr-3 bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <span class="text-sm font-medium text-gray-800 mr-1 leading-none">
                                            {{ $i }}
                                        </span>
                                        <i class="fas fa-star text-yellow-500 text-xs leading-none"></i>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-2.5 flex-1">
                                        <div class="bg-yellow-500 h-2.5 rounded-full"
                                            style="width: {{ $totalReviews > 0 ? ($ratingBreakdown[$i] / $totalReviews) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm text-gray-500 ml-3 w-10 text-right">{{ $totalReviews > 999 ? number_format($ratingBreakdown[$i] / 1000, 1) . 'k' : $ratingBreakdown[$i] }}</span>
                                </div>
                            @endfor
                        </div>

                        {{-- <div class="mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Key Criteria Ratings</h4>
                            <div class="flex flex-wrap gap-4">
                                <div
                                    class="text-center p-3 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-battery-half text-yellow-500 text-xl mb-1"></i>
                                    <p class="text-xs font-medium text-gray-600">Cleanliness</p>
                                    <p class="text-sm font-bold text-gray-800">4.5</p>
                                </div>
                                <div
                                    class="text-center p-3 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-tachometer-alt text-yellow-500 text-xl mb-1"></i>
                                    <p class="text-xs font-medium text-gray-600">Cost for Money</p>
                                    <p class="text-sm font-bold text-gray-800">4.4</p>
                                </div>
                                <div
                                    class="text-center p-3 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-palette text-yellow-500 text-xl mb-1"></i>
                                    <p class="text-xs font-medium text-gray-600">Overall Experience</p>
                                    <p class="text-sm font-bold text-gray-800">4.3</p>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                @endif

                <!-- Right Section: Guest Review Card -->
                <div class="w-full lg:w-2/3 lg:pl-6 space-y-6 pb-12">
                    <!-- Sort Filter -->
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="text-gray-600">Sort by:</span>
                        <select wire:model.live="reviewSort" class="border-none bg-gray-50 text-gray-700 font-medium text-sm rounded-lg focus:ring-green-500 focus:border-green-500 py-1 pl-3 pr-8 cursor-pointer hover:bg-gray-100 transition-colors">
                            <option value="newest">Date</option>
                            <option value="likes">Likes</option>
                            <option value="stars">Stars</option>
                        </select>
                    </div>
                    @forelse ($propertyReviews as $review)
                        <div
                            class="p-5 border border-gray-100 rounded-lg shadow-sm bg-white hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <!-- Guest Profile -->
                                <div class="flex space-x-3 items-center">
                                    <!-- Icon -->
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-sm font-semibold text-green-600">
                                        {{ substr($review['guest_name'] ?? 'U', 0, 1) }}
                                    </div>
                                    <!-- Guest Name + Review Date -->
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $review['guest_name'] }}</h4>
                                        <span class="text-xs text-gray-500">
                                            {{ $review['date_formatted'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Likes / Was this review helpful? -->
                                <div class="flex space-x-4 text-gray-400">
                                    <div
                                        class="flex items-center space-x-1 hover:text-blue-500 cursor-pointer transition-colors">
                                        <span class="text-sm">798</span>
                                        <i class="far fa-thumbs-up"></i>
                                    </div>
                                    <div
                                        class="flex items-center space-x-1 hover:text-red-500 cursor-pointer transition-colors">
                                        <span class="text-sm">738</span>
                                        <i class="far fa-thumbs-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Guest Star Rating -->
                            <div class="flex items-center space-x-2 mb-3">
                                <span
                                    class="px-2 py-0.5 bg-yellow-500 text-gray-900 text-xs font-semibold rounded-full">
                                    {{ $review['overall_rating'] }}
                                </span>
                                <div class="flex text-yellow-500 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review['overall_rating'])
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                </div>
                                {{-- <span class="text-sm text-gray-700 font-semibold">{{ $review['overall_rating'] }} / 5</span> --}}
                            </div>

                            <!-- Feedback Body -->
                            @if (!empty($review['comments']))
                                <p
                                    class="text-gray-700 text-sm leading-relaxed border-l-4 border-green-500 pl-4 py-1 italic mb-4">
                                    {{ $review['comments'] }}</p>
                            @endif

                            <!-- Category Ratings -->
                            @if (!empty($review['ratings']) && count($review['ratings']) > 1)
                                <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium">
                                    @foreach ($review['ratings'] as $rating)
                                        @if ($rating['type'] !== 'Overall Experience')
                                            <div class="flex items-center space-x-1 text-gray-600">
                                                <span class="font-semibold">{{ $rating['type'] }}:</span>
                                                <span class="text-yellow-600 font-bold">{{ $rating['value'] }}</span>
                                                <div class="flex">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $rating['value'])
                                                            <i class="fas fa-star text-yellow-400 text-[9px]"></i>
                                                        @else
                                                            <i class="far fa-star text-gray-300 text-[9px]"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 border border-gray-200 rounded-lg">
                            <i class="fas fa-comment-slash text-5xl mb-4 text-gray-300"></i>
                            <p class="text-xl font-semibold">No reviews available yet.</p>
                            <p class="text-md mt-1">Be the first to share your experience!</p>
                        </div>
                    @endforelse
                    <div class="h-8"></div>
                </div>
            </div>

            {{-- <div class="mt-6 text-center border-t border-gray-200 p-4">
                <x-button x-on:click="$wire.closeReviews()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-150">
                    Close Reviews
                </x-button>
            </div> --}}
        </div>
    </div>
</div>
