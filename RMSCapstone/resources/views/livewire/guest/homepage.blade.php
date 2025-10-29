<div class="bg-white text-gray-800 font-sans overflow-x-hidden">
    <!-- HERO -->
    <section
        class="relative min-h-[70vh] md:h-screen flex items-center justify-center bg-cover bg-center overflow-hidden"
        style="background-image: url('{{ asset('images/canopy-login2.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-transparent"></div>

        <div
            class="relative z-10 text-center px-6 py-24 max-w-3xl animate-on-scroll opacity-0 translate-y-10 transition-all duration-500 ease-out">
            <h1 class="text-yellow-50 text-4xl md:text-6xl font-extrabold mb-3 tracking-tight drop-shadow-md">
                {{ $companyName }}
            </h1>
            <p class="text-yellow-50 text-lg md:text-2xl font-light mb-8">
                Where nature meets elegance
            </p>
            <x-button
                class="px-10 py-4 !bg-yellow-50 hover:!bg-yellow-100 !text-green-700 !font-bold shadow-lg transition-transform transform hover:scale-105 rounded-full"
                href="#services">
                Explore Services
            </x-button>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="py-20 md:py-24 bg-gradient-to-b from-white to-green-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10 transition-all duration-700 ease-out">
                <h2 class="text-green-700 font-extrabold text-3xl tracking-tight mb-10 relative inline-block">
                    OUR SERVICES
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ([['Rooms', 'canopyretreat.png', 'Cozy Retreats', 'Experience comfort and tranquility in our thoughtfully designed rooms, perfect for a restful night surrounded by nature.', route('guest.reservation-form'), 'Book Now'], ['Events', 'wedding.png', 'Memorable Moments, Made Here', 'Host unforgettable events in our spacious halls, ideal for weddings, parties, corporate gatherings, and celebrations of all kinds.', route('guest.event-halls'), 'Request a Quote'], ['Spaces', 'pool-house2.png', 'Spaces for Rent', 'Find a variety of rental spaces to suit your needs, ideal for long-term or short-term stays with practical amenities and flexible options.', route('guest.houses'), 'View Spaces'], ['Activities', 'service-coffeeTour.png', 'Signature Experiences', 'From guided nature walks to rejuvenating wellness offerings, our services are designed to refresh your body, mind, and soul.', route('guest.activities'), 'View Activities']] as [$label, $img, $title, $desc, $link, $btn])
                    <div
                        class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-700 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10">
                        <div class="relative group overflow-hidden">
                            <img src="{{ asset('images/' . $img) }}" alt="{{ $label }}"
                                class="w-full h-56 object-cover transform transition-transform duration-700 group-hover:scale-110" />
                            <span
                                class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">{{ $label }}</span>
                        </div>
                        <div class="p-6 md:p-8 text-left">
                            <h3 class="text-xl font-semibold mb-3 text-gray-900">
                                {{ $title }}
                            </h3>
                            <p class="text-base text-gray-600 mb-6 leading-relaxed">
                                {{ $desc }}
                            </p>
                            <x-button class="px-6 py-2 !bg-green-700 hover:!bg-green-800 !text-white rounded-full"
                                href="{{ $link }}">
                                {{ $btn }}
                            </x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- DESCRIPTION -->
    <section id="description" class="py-20 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto space-y-12 px-6 sm:px-8">
            <!-- Experience -->
            <div
                class="flex flex-col md:flex-row items-center bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden animate-on-scroll opacity-0 translate-y-10">
                <img src="{{ asset('images/service-coffeeTour.png') }}" alt="Event Image"
                    class="object-cover w-full md:w-1/2 h-64 md:h-auto" />
                <div class="p-10 md:p-12 space-y-4">
                    <h5 class="text-2xl font-bold text-gray-900">
                        Signature Experiences
                    </h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Take your stay to the next level with our thoughtfully curated
                        activities—available as <b>add-ons to your room bookings.</b>
                    </p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        @foreach (['Nature Walks', 'Coffee Farm Tour', 'Coffee Class', 'Wellness Massage'] as $tag)
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div
                class="flex flex-col md:flex-row items-center bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden animate-on-scroll opacity-0 translate-y-10">
                <div class="p-10 md:p-12 space-y-4 order-2 md:order-1">
                    <h5 class="text-2xl font-bold text-gray-900">Offered Events</h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Host your next celebration in one of our versatile event halls —
                        perfect for weddings, birthdays, or team gatherings.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach (['Weddings', 'Birthdays', 'Team Buildings', 'Church Retreats'] as $tag)
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <x-button class="!bg-green-700 hover:!bg-green-800 !text-white rounded-full"
                        href="{{ route('guest.event-halls') }}">
                        Request a Quote
                    </x-button>
                </div>
                <img src="{{ asset('images/wedding.png') }}" alt="Event Image"
                    class="object-cover w-full md:w-1/2 h-64 md:h-auto order-1 md:order-2" />
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="py-20 md:py-24 bg-gradient-to-b from-green-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10 transition-all duration-700">
                <h2 class="text-green-700 font-extrabold text-3xl mb-12 tracking-tight">
                    What Our Guests Say
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Testimonial 1 -->
                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll opacity-0 translate-y-10">
                    <svg class="w-10 h-10 text-green-200 mb-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M6.5 10c-.221 0-.435.034-.64.093C6.425 6.208 8.89 3.447 12 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H6.5zM15.5 10c-.221 0-.435.034-.64.093C15.425 6.208 17.89 3.447 21 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H15.5z" />
                    </svg>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “The place is all you need if you want to relax
                        and stay away from all the stress in the city!
                        A great place to spend time with your family
                        and it is kid friendly. Will definitely come
                        back again soon”
                    </p>
                    <p class="text-gray-900 font-bold">— Angelica, Manila.</p>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll opacity-0 translate-y-10 delay-150">
                    <svg class="w-10 h-10 text-green-200 mb-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M6.5 10c-.221 0-.435.034-.64.093C6.425 6.208 8.89 3.447 12 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H6.5zM15.5 10c-.221 0-.435.034-.64.093C15.425 6.208 17.89 3.447 21 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H15.5z" />
                    </svg>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “Relaxing family vacation at Canopy Farm.
                        The place was clean, comfortable for our
                        group of 9 pax. Climate was cool and air
                        was fresh. A great place to unwind and
                        spend family time. Will come back..”
                    </p>
                    <p class="text-gray-900 font-bold">— Maika, Quezon.</p>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 animate-on-scroll opacity-0 translate-y-10 delay-300">
                    <svg class="w-10 h-10 text-green-200 mb-4 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M6.5 10c-.221 0-.435.034-.64.093C6.425 6.208 8.89 3.447 12 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H6.5zM15.5 10c-.221 0-.435.034-.64.093C15.425 6.208 17.89 3.447 21 3c-1.897 1.636-2.5 4.002-2.5 5.5 0 .5.166 1 .5 1.5H15.5z" />
                    </svg>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “It was very clean and the staff was friendly and helpful.
                        The Japanese inspired interior design is good for guests
                        with small children because everything is close to the
                        ground making it safe.”
                    </p>
                    <p class="text-gray-900 font-bold">— Donabelle.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-20 md:py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <h2
                class="text-green-700 font-extrabold text-3xl mb-10 tracking-tight animate-on-scroll opacity-0 translate-y-10">
                CONTACT US
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">
                <div
                    class="bg-gray-50 p-10 md:p-12 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 animate-on-scroll opacity-0 translate-y-10">


                    {{-- For sessionn messages --}}
                    @if (session('message'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                            class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                            {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ session('message') }}
                        </div>
                    @endif
                    <!-- Contact Form -->
                    <div>
                        <div class="lg:w-full px-2 mb-4 text-center">
                            <h2 class="text-green-700 font-bold text-2xl">CONTACT US</h2>
                        </div>
                        <form wire:submit.prevent="contactUs" class="space-y-3">
                            <input type="text" name="name" placeholder="Name" wire:model="name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <input type="email" name="email" placeholder="Email" wire:model="email"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <input type="tel" name="contact_number" placeholder="Phone Number"
                                wire:model="contact_number"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                            @error('contact_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <textarea name="message" placeholder="Message" wire:model="message"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600 resize-none"></textarea>
                            @error('message')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            <x-button wire:loading.attr="disabled">
                                <div class="flex items-center justify-center">
                                    <!-- Spinner -->
                                    <span wire:loading wire:target="contactUs" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>
                                    <!-- Button Text -->
                                    <span wire:loading.remove wire:target="contactUs">
                                        Send
                                    </span>
                                </div>
                            </x-button>
                        </form>
                    </div>


                </div>
                <div
                    class="overflow-hidden rounded-3xl shadow-lg h-full min-h-[450px] animate-on-scroll opacity-0 translate-y-10">
                    <iframe class="w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.2502287794205!2d120.885106586352!3d14.180116951815094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd83d64e074a45%3A0x74d9317979d3664b!2sThe%20Canopy%20Farm%20PH!5e0!3m2!1sen!2sph!4v1732550161255!5m2!1sen!2sph"
                        allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Intersection Observer for smooth fade/slide animations
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove("opacity-0", "translate-y-10");
                    entry.target.classList.add("opacity-100", "translate-y-0");
                    entry.target.classList.add("transition-all", "duration-500", "ease-out");
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        }
    );

    document.querySelectorAll(".animate-on-scroll").forEach((el, i) => {
        el.style.transitionDelay = `${i * 100}ms`;
        observer.observe(el);
    });
</script>
