<div class="p-6 rounded-lg shadow-md">
    {{-- <canvas id="ratingChart" height="120"></canvas> --}}

    <!-- Feedback Chart -->
    <h2 class="text-xl font-semibold mb-16">Feedback Chart</h2>
    <div class="w-full  mx-auto mt-10 mb-4 px-4">
        <!-- Y-Axis Labels & Grid -->
        <div class="relative h-60 border-l border-b border-gray-300">

            <!-- Grid lines -->
            <div class="absolute left-0 w-full h-full flex flex-col justify-between text-xs text-gray-500">
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">5.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">4.5</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">4.0</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-right pr-2">3.5</span>
                    <div class="border-t border-dashed border-gray-300 w-full"></div>
                </div>
            </div>

            <!-- Bars -->
            <div class="absolute bottom-0 left-10 right-0 flex items-end justify-around h-full px-4">
                <!-- Bar Item -->
                @foreach ($feedbackRatingTypes as $ratingType)
                <div class="flex flex-col items-center">
                    <div class="bar bg-yellow-400 w-6 rounded-t" data-value="5.0"></div>
                    <i class="fas fa-broom text-gray-600 mt-2 text-xs"></i>
                    <span class="text-xs mt-1">{{ $ratingType->rating_name}}</span>
                </div>
                @endforeach
            </div>

        </div>

    </div>

    <!-- Rating Types -->
    <div class="bg-white rounded-lg w-full mx-auto">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Average Rating</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($feedbackRatingTypes as $ratingType)
            <div class="flex items-center justify-between border p-4 rounded-md relative">
                <!-- Remove Button -->
                <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-xs font-bold"
                    wire:click="RemoveRatingType({{ $ratingType->id }})">
                    ×
                </button>

                <div class="flex items-center space-x-2 text-gray-700 font-medium">
                    <i class="fas fa-broom"></i>
                    <span>{{ $ratingType->rating_name }}</span>
                </div>
                <div class="flex items-center space-x-1">
                    <span class="text-sm text-gray-900 font-semibold">5.0</span>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <div class="mt-4">
        <x-button wire:click="openRatingTypeModal" icon="fas fa-plus">
            Add Rating Name
        </x-button>
    </div>


    <!-- Comments/Feedbacks -->
    <div class="mt-10">
        <h3 class="text-lg font-semibold mb-4">Comment Feedback</h3>
        <ul class="space-y-4">
            @foreach ($comments as $comment)
            <li class="flex items-start space-x-4 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/canopy-logo.png') }}" alt="User profile"
                        class="w-10 h-10 rounded-full object-cover">
                </div>

                <!-- Comment Content -->
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                            {{ $comment->transaction->transactionUser->last_name ?? '' }}
                        </p>
                        <span class="text-xs text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1">
                        {{ $comment->comments }}
                    </p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    <div>
        @if ($createRatingTypeModal)
        <div id="guestModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[600px] max-h-[90vh] overflow-y-auto">
                <h2 class="text-lg font-semibold mb-4 text-green-700">Add Rating Type</h2>

                <!-- Amount Paid -->
                <div class="mt-4">
                    <label class="block text-sm text-gray-700">Rating Type Name</label>
                    <input type="text" wire:model="rating_name"
                        class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md" required>
                    @error('rating_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center gap-2 mt-6">
                    <x-button type="button" wire:click="CloseRatingTypeModal"
                        class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                        Cancel
                    </x-button>
                    <x-button type="button" wire:click="CreateRatingType">
                        Save Changes
                    </x-button>
                </div>

            </div>
        </div>
        @endif
    </div>

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
