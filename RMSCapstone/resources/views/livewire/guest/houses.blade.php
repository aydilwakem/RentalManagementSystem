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


        <div class="grid grid-cols-1 gap-8">

            <!-- House Card 1 -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">

                <!-- Swiper Container -->
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/pool-house1.jpg') }}"
                                alt="House 1">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/pool-house2.png') }}"
                                alt="House 2">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/cozy-rooms.jpg') }}"
                                alt="House 3">
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                <div class="p-5 pb-3">
                    <!-- Property name and description -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Property 1</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequatur placeat dicta nemo quae ea
                        laboriosam, magni illum cum fuga doloremque eaque voluptates corrupti eos sint quia hic
                        explicabo quisquam. Qui?
                    </p>

                    <!-- Address -->
                    <div class="text-gray-700 text-lg mb-2">
                        <strong>Address:</strong> 1234 Canopy Street, Taguig City, Metro Manila
                    </div>

                    <!-- Monthly Rent -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-green-600 font-bold text-lg">₱25,000/month</span>
                    </div>

                    <!-- Facilities / Inclusions -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">25
                            SQM</span>
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">1 Master
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

            <!-- House Card 2 -->
            <div
                class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition mb-0">

                <!-- Swiper Container -->
                <div class="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/pool-house1.jpg') }}"
                                alt="House 1">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/pool-house2.png') }}"
                                alt="House 2">
                        </div>
                        <div class="swiper-slide">
                            <img class="w-full h-[300px] object-cover" src="{{ asset('images/cozy-rooms.jpg') }}"
                                alt="House 3">
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>

                <div class="p-5 pb-3">
                    <!-- Property name and description -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Property 1</h2>
                    <p class="text-gray-600 text-sm mb-4 text-justify">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequatur placeat dicta nemo quae ea
                        laboriosam, magni illum cum fuga doloremque eaque voluptates corrupti eos sint quia hic
                        explicabo quisquam. Qui?
                    </p>

                    <!-- Address -->
                    <div class="text-gray-700 text-lg mb-2">
                        <strong>Address:</strong> 1234 Canopy Street, Taguig City, Metro Manila
                    </div>

                    <!-- Monthly Rent -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-green-600 font-bold text-lg">₱25,000/month</span>
                    </div>

                    <!-- Facilities / Inclusions -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">25
                            SQM</span>
                        <span class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">1 Master
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
    </div>

    <script>
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
    </script>

</div>
