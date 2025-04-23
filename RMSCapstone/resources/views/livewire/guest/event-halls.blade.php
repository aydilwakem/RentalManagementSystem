<div class="max-w-5xl mx-auto px-4 py-7 mb-8">
    <h1 class="text-3xl font-bold text-green-700 text-center">Our Event Halls</h1>

    <!-- Event Hall Cards -->
    <div class="space-y-3">

        <!-- Event Hall Card 1 -->
        <div class="mx-auto px-4 py-3">
            @foreach ($eventHalls as $hall)
            <div
                class="flex flex-col md:flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 w-full">

                <!-- Image -->
                <img class="object-cover w-full md:w-2/5 h-[300px] md:h-[300px] rounded-t-lg md:rounded-none md:rounded-s-lg shrink-0"
                    src="{{ asset($hall->image ? 'storage/' . $hall->image : 'images/rms-default.png') }}"
                    alt="{{ $hall->name_number }}">

                <div class="flex flex-col justify-between px-6 py-2 w-full">
                    <h5 class=" text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $hall->name_number }}
                    </h5>

                    <!-- Event Description -->
                    <p class="text-base font-normal text-gray-700 dark:text-gray-400" style="text-align: justify;">
                        {{ $hall->description }}
                    </p>

                    <div class="mt-4 space-y-2">

                        <!-- Maximum Capacity -->
                        <p class="text-base font-normal text-gray-700 dark:text-gray-400">
                            <i class="fas fa-users mr-2"></i>{{ $hall->capacity}}
                        </p>

                        <!-- Rate -->
                        <div class="flex items-center mb-4">
                            <span class="font-bold text-gray-800 mr-2">Rate:</span>
                            <span class="text-green-600 font-bold text-lg">₱{{ $hall->amount }}</span>
                        </div>

                    </div>
                    <!-- Request a Quote Button -->
                    <div class="mt-3 text-right mb-2">
                        <x-button class=" transition duration-300" href="{{ route('guest.request-a-quote') }}">
                            Request a Quote
                        </x-button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>