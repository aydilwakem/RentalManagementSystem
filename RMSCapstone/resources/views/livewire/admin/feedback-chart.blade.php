<div class="p-6 rounded-lg shadow-md max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    <!-- Display Session Message -->
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
    @endif

    {{-- <canvas id="ratingChart" height="120"></canvas> --}}

    <!-- Feedback Chart -->
    <div>
        <h2 class="text-xl font-semibold mb-2">Feedback Chart</h2>
        <!-- Y-Axis Labels & Grid -->
        <div class="relative h-60 border-l border-b border-gray-300">

            <!-- Grid lines -->
            <div class="absolute left-0 w-full h-full flex flex-col justify-between text-xs text-gray-500">
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">5.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">4.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">3.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">2.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">1.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
            </div>

            <!-- Bars -->
            <div class="absolute bottom-0 left-10 right-0 flex items-end justify-around h-full px-4">
                <!-- Bar Item -->
                @foreach ($feedbackRatingTypes as $ratingType)
                <div class="flex flex-col items-center">
                    <i class="fas fa-broom text-gray-600 mt-2 text-xs dark:text-gray-200"></i>
                    <span class="text-xs mt-1">{{ $ratingType->rating_name }}</span>
                    <div class="bar bg-yellow-400 w-6 rounded-t" data-value="5.0"></div>
                </div>
                @endforeach
            </div>

        </div>

    </div>

    <!-- Rating Types -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-1">Rating Categories</h2>
        <div class="mb-3">
            <x-button wire:click="openRatingTypeModal" icon="fas fa-plus">
                Add Rating Category
            </x-button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($feedbackRatingTypes as $ratingType)
            <div
                class="flex items-center justify-between border p-4 rounded-md relative dark:bg-gray-700 dark:border-gray-600">
                <!-- Remove Button -->
                <button type="button"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-5 h-5 flex items-center justify-center text-lg focus:outline-none absolute right-2 translate-y-[-12px]"
                    wire:click="confirmDelete({{ $ratingType->id }})">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
                <div class="flex items-center space-x-2 text-gray-700 font-medium dark:text-white">
                    <i class="fas fa-star"></i>
                    <span>{{ $ratingType->rating_name }}</span>
                </div>
                {{-- <div class="flex items-center space-x-1">
                    <span class="text-sm text-gray-900 font-semibold">5.0</span>
                    <i class="fas fa-star text-yellow-400"></i>
                </div> --}}
            </div>
            @endforeach
        </div>
        <div>
            @if ($createRatingTypeModal)
            <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">

                        <!-- Header -->
                        <div
                            class=" bg-green-50 flex items-center border-b border-gray-200 px-6 py-4 dark:bg-gray-800 dark:border-gray-700">
                            <h2 class="text-2xl font-semibold text-center text-green-700 dark:text-green-200">Add Rating
                                Category
                                Name
                            </h2>
                        </div>
                        <div class="px-6 py-3">
                            <!-- Input -->
                            <div class="mt-2 mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-200">Rating
                                    Category
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="rating_name" placeholder="Ex. Cleanliness"
                                    class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-green-600 focus:border-green-600"
                                    required>
                                @error('rating_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center gap-2 mt-6 mb-2">
                            <x-button type="button" wire:click="CloseRatingTypeModal"
                                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                Cancel
                            </x-button>
                            <x-button type="button" wire:click="CreateRatingType">
                                Create
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Guest Comments</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Pending Feedbacks -->
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-yellow-600">Pending Comments for Approval</h3>
                <p class="text-sm text-gray-500 mb-4">These comments were submitted by guests and are awaiting your
                    review.
                </p>

                <!-- Scrollable list -->
                <ul class="space-y-4 max-h-96 overflow-y-auto pr-1">
                    @forelse ($comments->where('status', 'pending') as $comment)
                    <li
                        class="flex items-start gap-4 bg-yellow-50 p-4 rounded-lg border border-yellow-200 shadow-sm hover:shadow-md ease-in-out duration-300">
                        <img src="{{ asset('images/canopy-logo.png') }}" class="w-10 h-10 rounded-full object-cover">

                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                                    {{ $comment->transaction->transactionUser->last_name ?? '' }}
                                </p>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>


                            <!-- Display overall rating as stars -->
                            <div class="flex items-center mt-1">
                                @php
                                $overallRating = optional(
                                $comment->feedbackRatings->firstWhere('ratingType.rating_name', 'Overall Experience')
                                )->rating_value;
                                @endphp

                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$overallRating) <i
                                    class="fas fa-star text-yellow-400 text-[12px]"></i>
                                    @else
                                    <i class="far fa-star text-gray-300 text-[12px]"></i>
                                    @endif
                                    @endfor

                                    {{-- <span class="text-xs text-gray-500 ml-2">{{ $overallRating }}/5</span> --}}
                            </div>


                            <p class="text-sm text-gray-700 mt-2">
                                {{ $comment->comments !== '' ? $comment->comments : 'No comment provided.' }}
                            </p>

                            <div class="flex gap-2 mt-3">
                                <x-button wire:click="approveComment({{ $comment->id }})">Approve</x-button>
                                <x-danger-button wire:click="rejectComment({{ $comment->id }})">Reject
                                </x-danger-button>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm">No pending comments.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Approved Feedbacks -->
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-green-600">Approved Comments</h3>
                <p class="text-sm text-gray-500 mb-4">These comments are visible to guests on the booking site.</p>

                <ul class="space-y-4 max-h-96 overflow-y-auto pr-1">
                    @forelse ($comments->where('status', 'approved') as $comment)
                        <li
                            class="flex items-start gap-4 p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md ease-in-out duration-300">
                            <img src="{{ asset('images/canopy-logo.png') }}"
                                class="w-10 h-10 rounded-full object-cover">

                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                                    {{ $comment->transaction->transactionUser->last_name ?? '' }}
                                </p>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 mt-2">
                                {{ $comment->comments }}
                            </p>
                        </div>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm">No approved comments.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Rejected Feedbacks -->
            <div class="bg-white rounded-xl border p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-red-600">Rejected Comments</h3>
                <p class="text-sm text-gray-500 mb-4">These comments were declined and will not be shown publicly.</p>

                <ul class="space-y-4 max-h-96 overflow-y-auto pr-1">
                    @forelse ($comments->where('status', 'rejected') as $comment)
                        <li
                            class="flex items-start gap-4 p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md ease-in-out duration-300">
                            <img src="{{ asset('images/canopy-logo.png') }}"
                                class="w-10 h-10 rounded-full object-cover">

                        <div class="flex-1">
                            <div class="flex justify-between">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                                    {{ $comment->transaction->transactionUser->last_name ?? '' }}
                                </p>
                                <span class="text-xs text-gray-500">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-700 mt-2">
                                {{ $comment->comments }}
                            </p>
                        </div>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm">No rejected comments.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
        <x-slot name="title">
            {{ __('Delete Rating Category') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="RemoveRatingType" wire:loading.attr="disabled">
                {{ __('Delete Rating Category') }}
            </x-danger-button>

        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
        <x-slot name="title">
            {{ __('Unable to Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('This rating type is currently in use and cannot be deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>

<!-- Script -->
<script>
    // Max bar height in pixels (adjust to fit container height)
    const maxHeightPx = 50;

    // Max value of the scale
    const maxValue = 5.0;

    // Select all bars
    const bars = document.querySelectorAll('.bar');

    bars.forEach(bar => {
        // Get the value from data-value attribute
        const value = parseFloat(bar.getAttribute('data-value'));

        // Calculate the height proportionally
        const height = (value / maxValue) * maxHeightPx;

        // Set the height style dynamically
        bar.style.height = height + 'px';
    });
</script>


<!-- Feedback Chart -->
{{-- <div class=" mx-auto grid grid-cols-2 gap-6 mb-4">
    <!-- Item -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-broom"></i>
            <span>Cleanliness</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-bullseye"></i>
            <span>Accuracy</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-door-open"></i>
            <span>Check-in</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>

        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-comments"></i>
            <span>Communication</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-map-marker-alt"></i>
            <span>Location</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <i class="far fa-star"></i>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-gray-700 font-medium">
            <i class="fas fa-tags"></i>
            <span>Value</span>
        </div>
        <div class="flex space-x-1 text-yellow-400">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
        </div>
    </div>
</div> --}}


{{--
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const chartData = @json($chartData);
        const labels = chartData[0]?.dates ?? [];

        const datasets = chartData.map(item => ({
            label: item.label,
            data: item.data,
            fill: false,
            borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
            tension: 0.4
        }));

        new Chart(document.getElementById('ratingChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Average Ratings by Type Over Time'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });
    });
</script> --}}
