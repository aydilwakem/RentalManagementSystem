<div class="bg-white text-gray-800 font-sans overflow-x-hidden">

    <!-- HERO -->
    <section
        class="relative min-h-[70vh] md:h-screen flex items-center justify-center bg-cover bg-center overflow-hidden"
        style="background-image: url('{{ asset('images/canopy-login2.webp') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/40 to-transparent"></div>

        <div class="relative z-10 text-center px-6 py-24 max-w-3xl animate-on-scroll opacity-0 translate-y-10">
            <h1 class="text-yellow-50 text-4xl md:text-6xl font-extrabold mb-3 tracking-tight drop-shadow-md">
                {{ $companyName }}
            </h1>
            <p class="text-yellow-50 text-lg md:text-2xl font-light mb-8">
                Where nature meets elegance
            </p>
            <x-button
                class="px-10 py-4 !bg-yellow-50 hover:!bg-yellow-100 !text-green-700 !font-bold shadow-lg transition-transform duration-150 transform hover:scale-105 rounded-full"
                href="#services">
                Explore Services
            </x-button>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="py-20 md:py-24 bg-gradient-to-b from-white to-yellow-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10">
                <h2 class="text-green-700 font-extrabold text-3xl tracking-tight mb-10 relative inline-block">
                    OUR SERVICES
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Rooms -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="{{ asset('images/canopy-retreat.jpg') }}" alt="Rooms"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Rooms</span>
                    </div>
                    <div class="p-6 md:p-8 text-left flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Cozy Retreats</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Experience comfort and tranquility in our thoughtfully designed rooms, perfect for a restful
                            night surrounded by nature.
                        </p>
                        <div class="mt-auto">
                            <x-button href="{{ route('guest.reservation-form') }}">
                                Book a room
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Day Tour -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="{{ asset('images/Infinity Pool(4).jpg') }}" alt="Day Tour"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Day
                            Tour</span>
                    </div>
                    <div class="p-6 md:p-8 text-left flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Quick Getaways</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Surrounded yourself with nature, enjoy refreshing swims and outdoor lounges perfect for
                            short escapes or quick relaxation.
                        </p>
                        <div class="mt-auto">
                            <x-button href="{{ route('guest.day-tour-reservation') }}">
                                Book a Day Tour
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Activities -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="{{ asset('images/service-coffeeTour.png') }}" alt="Activities"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Activities</span>
                    </div>
                    <div class="p-6 md:p-8 text-left flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Signature Experiences</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            From guided nature walks to rejuvenating wellness offerings, our services are designed to
                            refresh your body, mind, and soul.
                        </p>
                        <div class="mt-auto">
                            <x-button href="{{ route('guest.activities') }}">
                                View Activities
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Events -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="{{ asset('images/event-hall.jpg') }}" alt="Events"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Events</span>
                    </div>
                    <div class="p-6 md:p-8 text-left flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Memorable Moments</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Host unforgettable events in our spacious halls, ideal for weddings, parties, corporate
                            gatherings, and celebrations of all kinds.
                        </p>
                        <div class="mt-auto">
                            <x-button href="{{ route('guest.event-halls') }}">
                                Request a Quote
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DESCRIPTION -->
    <section id="description" class="py-20 md:py-24 bg-gradient-to-b from-yellow-50 to-green-50 ">
        <div class="max-w-7xl mx-auto space-y-12 px-6 sm:px-8">

            <!-- Experience -->
            <div
                class="flex flex-col md:flex-row items-stretch bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out overflow-hidden animate-on-scroll opacity-0 translate-y-10">
                <div class="w-full md:w-[400px] h-64 md:h-auto flex">
                    <img src="{{ asset('images/service-coffeeTour.png') }}" alt="Event Image"
                        class="object-cover w-full h-full" />
                </div>
                <div class="p-10 md:p-12 space-y-4 flex-1 flex flex-col justify-center">
                    <h5 class="text-2xl font-bold text-gray-900">Signature Experiences</h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Take your stay to the next level with our thoughtfully curated activities. Whether you're
                        looking to unwind, explore, or simply make the most of
                        your time in nature, we offer a variety of choices to suit your mood and interests. From
                        hands-on coffee tours and nature walks to farm experiences and local craft sessions, each
                        activity is designed to enrich your visit and create lasting memories.
                    </p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Nature
                            Walks</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Coffee
                            Farm Tour</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Coffee
                            Class</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Wellness
                            Massage</span>
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div
                class="flex flex-col md:flex-row items-stretch bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out overflow-hidden animate-on-scroll opacity-0 translate-y-10">
                <div class="p-10 md:p-12 space-y-4 order-2 md:order-1 flex-1 flex flex-col justify-center">
                    <h5 class="text-2xl font-bold text-gray-900">Memorable Moments, Made Here</h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Host your next celebration in one of our versatile event halls, perfect for weddings, birthdays,
                        corporate events, or intimate gatherings. We offer a range of indoor and outdoor venues to suit
                        your needs. Our team customizes event packages based on your vision—just share your ideas, and
                        we’ll handle the rest. Request a quote today and let’s bring your event to life!

                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Weddings</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Birthdays</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Team
                            Buildings</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Church
                            Retreats</span>
                    </div>
                </div>
                <div class="w-full md:w-[400px] h-64 md:h-auto flex order-1 md:order-2">
                    <img src="{{ asset('images/wedding.png') }}" alt="Event Image"
                        class="object-cover w-full h-full" />
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="py-20 md:py-24 bg-gradient-to-b from-green-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10">
                <h2 class="text-green-700 font-extrabold text-3xl mb-12 tracking-tight">
                    WHAT OUR GUESTS SAY
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “The place is all you need if you want to relax
                        and stay away from all the stress in the city!
                        A great place to spend time with your family
                        and it is kid friendly. Will definitely come
                        back again soon”
                    </p>
                    <p class="text-gray-900 font-bold">— Angelica, Manila.</p>
                </div>

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “Relaxing family vacation at Canopy Farm.
                        The place was clean, comfortable for our
                        group of 9 pax. Climate was cool and air
                        was fresh. A great place to unwind and
                        spend family time. Will come back..”
                    </p>
                    <p class="text-gray-900 font-bold">— Donabelle</p>
                </div>

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        “It was very clean and the staff was friendly and helpful.
                        The Japanese inspired interior design is good for guests
                        with small children because everything is close to the
                        ground making it safe.”
                    </p>
                    <p class="text-gray-900 font-bold">— Maika, Quezon.</p>
                </div>


            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-20 md:py-24 bg-white overflow-hidden">
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="fixed top-20 right-4 px-4 py-2 rounded-lg shadow-lg z-50
                {{ session('alert-type') === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <h2
                class="text-green-700 font-extrabold text-3xl mb-10 tracking-tight animate-on-scroll opacity-0 translate-y-10">
                CONTACT US
                <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">
                <div
                    class="bg-gray-50 p-10 md:p-12 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <div class="lg:w-full px-2 mb-4 text-center">
                        <h2 class="text-green-700 font-bold text-2xl">Message Here</h2>
                    </div>
                    <form wire:submit.prevent="contactUs" class="space-y-3">
                        <input type="text" name="name" placeholder="Name" wire:model="name" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        <input type="email" name="email" placeholder="Email" wire:model="email" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        <input type="tel" name="contact_number" placeholder="Phone Number"
                            wire:model="contact_number" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            inputmode="numeric" maxlength="11"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600" />
                        <textarea name="message" placeholder="Message" wire:model="message" rows="3" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-600 focus:border-green-600 resize-none"></textarea>

                        <x-button wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="contactUs">Send</span>
                            <span wire:loading wire:target="contactUs">
                                <svg class="animate-spin h-5 w-5 text-white inline-block" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                            </span>
                        </x-button>
                    </form>
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

{{-- Scroll Animation --}}
<script>
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove("opacity-0", "translate-y-10");
                    entry.target.classList.add("opacity-100", "translate-y-0");

                    entry.target.style.transition = "all 700ms cubic-bezier(0.25, 0.1, 0.25, 1)";

                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        }
    );

    document.querySelectorAll(".animate-on-scroll").forEach((el, i) => {
        el.style.transitionDelay = `${i * 150}ms`;
        observer.observe(el);
    });
</script>
