<x-guest-layout>

    @include('guest.guest-navigation-menu')

    <div class="max-w-7xl mx-auto px-4 py-7 mb-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-green-700 mb-3 text-center">Our Activities</h1>
            <p class="text-lg text-gray-700 text-center mb-3">
                These activities are <b>add-ons</b> to your bookings, enhancing your experience during your stay at Canopy
                Farm.<br> Book a room now and enjoy a variety of exciting experiences!
            </p>
            <x-button class="mb-8">
                Book Room Now
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-cover" src="{{ asset('images/service-coffeeTour.png') }}" alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Coffee Farm Tour with Coffee Class</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">₱1500</span>
                    </div>
                </div>
            </div>

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-cover" src="{{ asset('images/service-coffeeClass.png') }}"
                    alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Coffee Tasting Class</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">₱700</span>
                    </div>
                </div>
            </div>

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-cover" src="{{ asset('images/service-massage.png') }}" alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Massage with Ventosa</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">₱700/hr</span>
                    </div>
                </div>
            </div>

            <!-- Activity Card -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">
                <img class="w-full h-48 object-cover" src="{{ asset('images/service-massage2.jpg') }}"
                    alt="Nature Walk">
                <div class="p-5 pb-3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Therapeutic Massage</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">Lorem ipsum dolor sit amet consectetur
                        adipisicing
                        elit. Porro aperiam ex provident officia aspernatur ipsum laborum temporibus quae totam vero,
                        maxime, similique a atque assumenda tempora nisi ut error fugiat?</p>
                    <div class="text-right">
                        <span class="text-green-600 font-bold text-lg">₱500/hr</span>
                    </div>
                </div>
            </div>

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

    @include('guest.footer')

</x-guest-layout>
