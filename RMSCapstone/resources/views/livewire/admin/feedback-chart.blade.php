<div>
    <h2 class="text-xl font-semibold mb-4">Feedback Ratings Over Time</h2>
    <canvas id="ratingChart" height="120"></canvas>

    <div class="mt-10">
        <h3 class="text-lg font-bold mb-4">Recent Comments</h3>
        <ul class="space-y-4">
            @foreach ($comments as $comment)
                <li class="flex items-start space-x-4 bg-white p-4 rounded shadow-sm">
                    <!-- Profile Image -->
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/canopy-logo.png') }}" alt="User profile"
                            class="w-10 h-10 rounded-full object-cover">
                    </div>

                    <!-- Comment Content -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                                    {{ $comment->transaction->transactionUser->last_name ?? '' }}
                                </p>
                            </div>
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


</div>




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
