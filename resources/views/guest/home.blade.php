<x-guest-layout>

    @include('guest.guest-navigation-menu')

    <!-- HERO -->
    <section class="h-screen bg-cover bg-center relative pt-20"
        style="background-image: url('{{ asset('images/canopy-home2.png') }}');">
        <div class="absolute inset-0 flex items-center justify-center text-center px-4">
            <div>
                <h1 class="text-yellow-50 text-4xl md:text-6xl font-bold mb-2"
                    style="text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4), -1px -1px 2px rgba(0, 0, 0, 0.4);">Canopy Farm
                </h1>
                <p class="text-yellow-50 text-lg md:text-2xl font-light mb-6 drop-shadow-2xl">Where nature meets elegance
                </p>
                <x-button class="px-10 py-4 !bg-yellow-50 hover:!bg-yellow-100 !text-green-700 !font-bold"
                    href="#services">
                    Explore Services
                </x-button>
            </div>
        </div>
    </section>


    <!-- SERVICES -->
    <section id="services">
        <div class="py-5 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="lg:w-full px-2 mb-4 text-center">
                    <h2 class="text-green-700 font-bold text-xl">OUR SERVICES</h2>
                </div>
                <div class="relative">
                    <!-- Swiper Container -->
                    <div class="swiper w-full">
                        <div class="swiper-wrapper">
                            <!-- Card 1 -->
                            <div class="swiper-slide bg-white rounded-2xl overflow-hidden border">
                                <div class="relative">
                                    <img src="{{ asset('images/canopyretreat.png') }}" alt="Rooms"
                                        class="w-full h-48 object-cover">
                                    <span
                                        class="absolute top-3 left-3 bg-white text-green-700 text-xs px-3 py-1 rounded-full border">Rooms</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-lg font-semibold mb-2 text-center">Cozy Retreats</h3>
                                    <p class="text-sm text-gray-600 mb-3 text-center">
                                        Experience comfort and tranquility in our thoughtfully designed rooms, perfect
                                        for a restful night surrounded by nature.
                                    </p>
                                    <div class="flex justify-center">
                                        <x-button href="#">
                                            Book Now
                                        </x-button>
                                    </div>
                                </div>

                            </div>

                            <!-- Card 2 -->
                            <div class="swiper-slide bg-white rounded-2xl overflow-hidden border">
                                <div class="relative">
                                    <img src="{{ asset('images/wedding.png') }}" alt="Events"
                                        class="w-full h-48 object-cover">
                                    <span
                                        class="absolute top-3 left-3 bg-white text-green-700 text-xs px-3 py-1 rounded-full border">Events</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-lg font-semibold mb-2 text-center">Memorable Moments, Made Here</h3>
                                    <p class="text-sm text-gray-600 mb-3 text-center">Host unforgettable events in our
                                        spacious
                                        halls—ideal for weddings, parties, corporate gatherings, and celebrations of all
                                        kinds.</p>
                                    <div class="flex justify-center">
                                        <x-button href="#">
                                            Request a Quote
                                        </x-button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="swiper-slide bg-white rounded-2xl overflow-hidden border">
                                <div class="relative">
                                    <img src="{{ asset('images/pool-house2.png') }}" alt="AI"
                                        class="w-full h-48 object-cover">
                                    <span
                                        class="absolute top-3 left-3 bg-white text-green-700 text-xs px-3 py-1 rounded-full border">Houses</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-lg font-semibold mb-2 text-center">Nature-Inspired Stays</h3>
                                    <p class="text-sm text-gray-600 mb-3 text-center">Stay in our charming houses,
                                        perfect for long-term stays, blending rustic elegance with modern amenities for
                                        a truly immersive countryside experience.
                                    </p>
                                    <div class="flex justify-center">
                                        <x-button href="#">
                                            Rent House
                                        </x-button>
                                    </div>

                                </div>
                            </div>

                            <!-- Card 4 -->
                            <div class="swiper-slide bg-white rounded-2xl overflow-hidden border">
                                <div class="relative">
                                    <img src="{{ asset('images/service-coffeeTour.png') }}" alt="Activitied"
                                        class="w-full h-48 object-cover">
                                    <span
                                        class="absolute top-3 left-3 bg-white text-green-700 text-xs px-3 py-1 rounded-full border">Activities</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-lg font-semibold mb-2 text-center">Signature Experiences</h3>
                                    <p class="text-sm text-gray-600 mb-3 text-center">From guided nature walks to
                                        rejuvenating
                                        wellness offerings, our services are designed to refresh your body, mind, and
                                        soul.</p>
                                    <div class="flex justify-center">
                                        <x-button href="#">
                                            View Activities
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <!-- Pagination -->
                        <div class="swiper-pagination mt-32"></div>

                        <!-- Navigation -->
                        <div class="swiper-button-prev !text-white !left-0 md:!left-4"></div>
                        <div class="swiper-button-next !text-white !right-0 md:!right-4"></div> --}}
                    </div>

                </div>
            </div>
        </div>

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- Swiper Init Script -->
        <script>
            new Swiper(".swiper", {
                loop: true,
                spaceBetween: 30,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        </script>
    </section>


    <section id="description" class="py-6 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <a href="#"
                class="flex flex-col md:flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 w-full">

                <!-- Image -->
                <img class="object-cover w-full md:w-1/2 h-64 md:h-auto rounded-t-lg md:rounded-none md:rounded-s-lg"
                    src="{{ asset('images/service-coffeeTour.png') }}" alt="Event Image">

                <!-- Content -->
                <div class="flex flex-col justify-between p-6 w-full">
                    <!-- Event Title -->
                    <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Signature Experiences
                    </h5>

                    <!-- Event Description -->
                    <p class="text-base font-normal text-gray-700 dark:text-gray-400 mb-4" style="text-align: justify;">
                        Take your stay to the next level with our thoughtfully curated activities—available as add-ons
                        to your room bookings. Whether you're looking to unwind, explore, or simply make the most of
                        your time in nature, we offer a variety of choices to suit your mood and interests. From
                        hands-on coffee tours and nature walks to farm experiences and local craft sessions, each
                        activity is designed to enrich your visit and create lasting memories. </p>

                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Nature
                            Walks</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Coffee
                            Farm Tour</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Coffee
                            Class</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Wellness
                            Massage</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="max-w-7xl mx-auto p-4">
            <a href="#"
                class="flex flex-col md:flex-row items-center bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 w-full">
                <div class="flex flex-col justify-between p-6 w-full">
                    <h5 class="mb-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Offered Events
                    </h5>
                    <p class="text-base font-normal text-gray-700 dark:text-gray-400" style="text-align: justify;">
                        Host your next celebration in one of our versatile event halls, perfect for any occasion—be it
                        weddings, birthdays, corporate gatherings, or intimate get-togethers. We offer a variety of
                        spaces to accommodate your needs, from elegant indoor settings to scenic outdoor venues. Our
                        team can curate personalized event packages based on your specific requirements. Simply share
                        your vision, and we’ll handle the rest. Request a quote today and let’s bring your event to
                        life.
                    </p>
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 py-4 justify-between">
                        <div class="items-center space-x-2">
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Birthdays
                            </span>
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">
                                Weddings
                            </span>
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">
                                Team Buildings
                            </span>
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Church
                                Retreat
                            </span>
                        </div>
                        <div>
                            <x-button>Request a Quote</x-button>
                        </div>
                    </div>

                </div>
                <img class="object-cover w-full md:w-1/2 h-64 md:h-auto rounded-t-lg md:rounded-none md:rounded-s-lg"
                    src="{{ asset('images/wedding.png') }}" alt="Blog Cover">

            </a>
        </div>
    </section>





    <!-- CONTACT FORM + MAP -->
    <section id="contact" class="py-10 bg-white">
        <div class="py-5">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Contact Form -->
                    <div>
                        <div class="lg:w-full px-2 mb-4 text-center">
                            <h2 class="text-green-700 font-bold text-xl">CONTACT US</h2>
                        </div>
                        <form id="request" class="space-y-3">
                            <input type="text" name="Name" placeholder="Name"
                                class="w-full p-3 border rounded-md focus:ring focus:ring-green-300">
                            <input type="email" name="Email" placeholder="Email"
                                class="w-full p-3 border rounded-md focus:ring focus:ring-green-300">
                            <input type="tel" name="Phone Number" placeholder="Phone Number"
                                class="w-full p-3 border rounded-md focus:ring focus:ring-green-300">
                            <textarea name="Message" placeholder="Message"
                                class="w-full p-3 border rounded-md focus:ring focus:ring-green-300 resize-none"></textarea>
                            <button type="submit"
                                class="w-full px-4 py-2 text-white bg-green-700 hover:bg-green-800 rounded-md">
                                Send
                            </button>
                        </form>
                    </div>

                    <!-- Google Map -->
                    <div>
                        <iframe class="w-full h-[450px] rounded-md shadow-md"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.2502287794205!2d120.885106586352!3d14.180116951815094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd83d64e074a45%3A0x74d9317979d3664b!2sThe%20Canopy%20Farm%20PH!5e0!3m2!1sen!2sph!4v1732550161255!5m2!1sen!2sph"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <footer class="bg-green-800 text-white text-center py-3">
        <div class="container mx-auto flex flex-col items-center">
            <div class="flex space-x-3 mb-2">
                <a href="https://www.facebook.com/thecanopyfarmph" class="text-white hover:text-gray-200">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.tiktok.com/@canopyfarmph" class="text-white hover:text-gray-200">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.instagram.com/thecanopyfarmph/" class="text-white hover:text-gray-200">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            <div class="text-sm">
                <p>Email: <span class="font-medium">canopyfarmph@gmail.com</span></p>
                <p>Phone: <span class="font-medium">0962-447-9893</span></p>
                <p>Address: 006 San Gregorio Extension, Brgy. Buna Cerca, Indang, Philippines</p>
            </div>
            <p class="mt-2 text-xs">&copy; 2024 Canopy Farm. All rights reserved.</p>
        </div>
    </footer>

</x-guest-layout>
