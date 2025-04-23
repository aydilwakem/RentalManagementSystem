<div>
    <div class="max-w-7xl mx-auto px-4 py-7 mb-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-green-700 mb-3 text-center">Our Activities</h1>
            <p class="text-lg text-gray-700 text-center mb-3">
                These activities are <b>add-ons</b> to your bookings, enhancing your experience during your stay at
                Canopy
                Farm.<br> Book a room now and enjoy a variety of exciting experiences!
            </p>
            <x-button class="mb-8" href="{{ route('guest.rooms') }}">
                Book Room Now
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if (!empty($activities) && $activities->count())
            <!-- Activity Card -->
            @foreach ($activities as $activity)
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-cover"
                    src="{{ asset($activity->image ? 'storage/' . $activity->image : 'images/rms-default.png') }}"
                    alt="{{ $activity->name }}">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2"> {{ $activity->name }}</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">
                        @if (!empty($activity->description))
                        {{ $activity->description }}
                        @else
                        Try this activity only at Canopy Farm!
                        @endif
                    </p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">₱{{ number_format($activity->amount, 2)
                            }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            @else
            <p class="text-center text-gray-500 py-10">No activities available at the moment.</p>
            @endif


            {{-- FREE ACTIVITIES --}}

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-bottom" src="{{ asset('images/activity-bridge.png') }}"
                    alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Hanging Bridge</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">FREE</span>
                    </div>
                </div>
            </div>

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-center" src="{{ asset('images/activity-basement.png') }}"
                    alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Chill Out Game Room</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">FREE</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>