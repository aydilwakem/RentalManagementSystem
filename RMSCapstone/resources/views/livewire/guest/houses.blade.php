<div>
    <div class="max-w-4xl mx-auto px-4 py-7 mb-8">
        <h1 class="text-3xl font-bold text-green-700 mb-2 text-center">Houses for Lease</h1>

        <!-- Inquire Text -->
        <p class="text-lg text-gray-700 text-center mb-5">
            Inquire by sending an email to
            <span class="text-green-600">canopyfarm@gmail.com</span>
            or call this number <span class="text-green-600">09123456789</span> for more information and lease
            agreements.
        </p>

        @foreach ($houses as $house)
        <div class="grid grid-cols-1 gap-8">

            <!-- House Card 1 -->

            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">

                {{-- Image --}}
                <img class="w-full h-[300px] object-cover"
                    src="{{ asset($house->image ? 'storage/' . $house->image : 'images/rms-default.png') }}"
                    alt="{{ $house->name_number }}">

                <div class="p-5 pb-3">
                    <!-- Property name and description -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $house->name_number }}</h2>
                    @if (!empty($house->description))
                    {{ $house->description }}
                    @else
                    Rent Now!
                    @endif
                    </p>

                    <!-- Address -->
                    <div class="text-gray-700 text-lg mb-2">
                        <strong>Address:</strong> {{ $house->house_number }}, {{ $house->street }},
                        {{ $house->barangay }}, {{ $house->city_municipality }}, {{ $house->region }},
                        {{ $house->postal_code }}, {{ $house->country }}
                    </div>

                    <!-- Monthly Rent -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-green-600 font-bold text-lg">₱25,000/month</span>
                    </div>

                    <!-- Facilities / Inclusions -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">25
                            SQM</span>
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">1
                            Master
                            Bedroom</span>
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">2
                            Bedroom</span>
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">2
                            Bathroom</span>
                        <span
                            class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">Parking</span>
                        <span
                            class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">Garden</span>
                    </div>

                    <!-- CTA Buttons -->
                    {{-- <div class="mt-5 justify-between items-center flex">
                        <button class="text-green-600 font-semibold" wire:click="showPropertyDetails">
                            View Full Details
                        </button>
                        <button class="text-green-600 font-semibold" wire:click="showPropertyDetails">
                            Inquire Now
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            spaceBetween: 30,

            autoplay: {
                delay: 2000,
                pauseOnMouseEnter: true,
            },

            pagination: {
                el: '.swiper-pagination',
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

        });
    </script> --}}

</div>