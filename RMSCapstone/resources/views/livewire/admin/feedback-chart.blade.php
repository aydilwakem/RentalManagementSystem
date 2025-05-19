<div>
    <h2 class="text-xl font-semibold mb-4">Feedback Ratings Over Time</h2>
    <canvas id="ratingChart" height="120"></canvas>

    <div class="mt-10">
        <h3 class="text-lg font-bold">Recent Comments</h3>
        <ul class="mt-2 space-y-2">
            @foreach ($comments as $comment)
                <div>
                    <strong>
                        {{ $comment->transaction->transactionUser->first_name ?? 'Unknown' }}
                        {{ $comment->transaction->transactionUser->last_name ?? '' }}
                    </strong>
                    <p>{{ $comment->comments }}</p>
                </div>
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