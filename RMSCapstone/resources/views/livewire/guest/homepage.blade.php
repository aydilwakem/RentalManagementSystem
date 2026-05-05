<div class="bg-white text-gray-800 font-sans overflow-x-hidden">

    <!-- HERO SECTION -->
    <section class="relative h-screen flex flex-col justify-center items-center bg-cover bg-center overflow-hidden"
        style="background-image: url('{{ asset('images/canopy-login2.webp') }}');">

        <!-- 1. Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- 2. Main Content -->
        <div
            class="relative z-10 text-center px-4 max-w-5xl mx-auto animate-on-scroll opacity-0 translate-y-10 transition-all duration-1000 delay-300">

            <!-- Icon -->
            {{-- <div class="mb-6 opacity-90">
                <i class="fas fa-leaf text-4xl text-white drop-shadow-md"></i>
            </div> --}}

            <h1 class="text-white text-5xl md:text-7xl font-serif font-bold mb-6 tracking-tight drop-shadow-xl">
                {{ $companyName }}
            </h1>

            <p
                class="text-white text-md md:text-xl font-light tracking-[0.1em] uppercase mb-12 drop-shadow-md opacity-90">
                Your striking business tagline here
            </p>

            <a href="#services"
                class="inline-block border-b-2 tracking-wide border-white pb-1 text-white text-lg font-medium hover:text-green-400 hover:border-green-400 transition-colors duration-300">
                Explore Services
            </a>
        </div>

        <!-- Booking Section -->
        <div
            class="absolute bottom-0 left-0 w-full z-20 bg-white backdrop-blur-md border-t border-white/20 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <form action="{{ route('guest.reservation-form') }}" method="GET" x-data="{ loading: false }"
                    @submit="loading = true" class="flex flex-col md:flex-row items-center gap-4 md:gap-8">

                    <div
                        class="hidden md:block text-green-900 font-medium text-xl whitespace-nowrap pr-4 border-r border-gray-300">
                        Book your stay
                    </div>

                    <div class="w-full grid grid-cols-2 md:grid-cols-3 gap-4 flex-grow">
                        <!-- Check In -->
                        <div class="relative group">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 group-hover:text-green-700 transition-colors">Check
                                In</label>
                            <input type="date" name="check_in" required value="{{ $check_in }}"
                                min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}"
                                class="w-full bg-transparent border-0 border-b border-gray-300 p-0 pb-1 text-gray-800 font-medium focus:ring-0 focus:border-green-600 transition-colors cursor-pointer">
                        </div>

                        <!-- Check Out -->
                        <div class="relative group">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 group-hover:text-green-700 transition-colors">Check
                                Out</label>
                            <input type="date" name="check_out" required value="{{ $check_out }}"
                                min="{{ isset($check_in_date) ? \Carbon\Carbon::parse($check_in_date)->addDay()->format('Y-m-d') : \Carbon\Carbon::now('Asia/Manila')->addDay()->format('Y-m-d') }}"
                                class="w-full bg-transparent border-0 border-b border-gray-300 p-0 pb-1 text-gray-800 font-medium focus:ring-0 focus:border-green-600 transition-colors cursor-pointer">
                        </div>

                        <!-- Guest Count -->
                        <div class="relative group hidden md:block">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 group-hover:text-green-700 transition-colors">Guests</label>
                            <select name="guests"
                                class="w-full bg-transparent border-0 border-b border-gray-300 p-0 pb-1 text-gray-800 font-medium focus:ring-0 focus:border-green-600 cursor-pointer">
                                <option>2 Guests</option>
                                <option>4 Guests</option>
                                <option>6 Guests</option>
                                <option>8 Guests</option>
                                <option>10 Guests</option>
                                <option>20 Guests</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    {{-- <button
                        class="w-full md:w-auto bg-green-700 text-white px-8 py-4 rounded-lg font-bold hover:bg-green-800 transition-all shadow-lg hover:shadow-green-900/30 whitespace-nowrap flex items-center justify-center gap-2">
                        <span>Check Availability</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button> --}}
                    <div class="relative">
                        <x-button type="submit"
                            class="w-full md:w-auto bg-green-700 text-white px-8 py-4 rounded-lg font-bold hover:bg-green-800 transition-all shadow-lg hover:shadow-green-900/30 whitespace-nowrap flex items-center justify-center gap-2">
                            <span>Check Availability</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </x-button>

                        <div x-show="loading" style="display: none;"
                            class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg z-10 backdrop-blur-[1px]">
                            <span class="text-sm text-green-700 font-semibold mr-2">Checking availability...</span>
                            <i class="fas fa-spinner fa-spin text-green-700 text-lg"></i>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="py-20 md:py-24 bg-gradient-to-b from-white to-yellow-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10">
                <h2 class="text-green-700 font-bold tracking-wide uppercase mb-10 relative inline-block text-3xl">
                    OUR SERVICES
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Service 1 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Service 1"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Service 1</span>
                    </div>
                    <div class="p-6 md:p-8 text-center flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Service 1 Title</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Service 1 description. This is a placeholder description for the first service.
                        </p>
                        <div class="mt-auto">

                            <div x-data="{ loading: false }" class="w-full">
                                <x-button href="#" @click="loading = true"
                                    class="w-full py-3 text-md font-bold shadow-lg hover:shadow-green-900/20 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2"
                                    ::class="{ 'opacity-75 cursor-wait pointer-events-none': loading }">

                                    <span x-show="!loading">
                                        Learn More
                                    </span>

                                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service 2 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Service 2"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Service 2</span>
                    </div>
                    <div class="p-6 md:p-8 text-center flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Service 2 Title</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Service 2 description. This is a placeholder description for the second service.
                        </p>
                        <div class="mt-auto">
                            <div x-data="{ loading: false }" class="w-full">
                                <x-button href="#" @click="loading = true"
                                    class="w-full py-3 text-md font-bold shadow-lg hover:shadow-green-900/20 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2"
                                    ::class="{ 'opacity-75 cursor-wait pointer-events-none': loading }">

                                    <span x-show="!loading">
                                        Learn More
                                    </span>

                                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service 3 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Service 3"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Service 3</span>
                    </div>
                    <div class="p-6 md:p-8 text-center flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Service 3 Title</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Service 3 description. This is a placeholder description for the third service.
                        </p>
                        <div class="mt-auto">
                            <div x-data="{ loading: false }" class="w-full">
                                <x-button href="#" @click="loading = true"
                                    class="w-full py-3 text-md font-bold shadow-lg hover:shadow-green-900/20 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2"
                                    ::class="{ 'opacity-75 cursor-wait pointer-events-none': loading }">

                                    <span x-show="!loading">
                                        Learn More
                                    </span>

                                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service 4 -->
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-transform duration-150 ease-out transform hover:-translate-y-2 animate-on-scroll opacity-0 translate-y-10 flex flex-col">
                    <div class="relative group overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Service 4"
                            class="w-full h-56 object-cover" />
                        <span
                            class="absolute top-4 left-4 bg-white/90 text-green-700 text-xs px-3 py-1 rounded-md border font-medium shadow-sm">Service 4</span>
                    </div>
                    <div class="p-6 md:p-8 flex flex-col flex-grow text-center">
                        <h3 class="text-xl font-semibold mb-3 text-gray-900">Service 4 Title</h3>
                        <p class="text-base text-gray-600 mb-6 leading-relaxed flex-grow">
                            Service 4 description. This is a placeholder description for the fourth service.
                        </p>
                        <div class="mt-auto">
                            <div x-data="{ loading: false }" class="w-full">
                                <x-button href="#" @click="loading = true"
                                    class="w-full py-3 text-md font-bold shadow-lg hover:shadow-green-900/20 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2"
                                    ::class="{ 'opacity-75 cursor-wait pointer-events-none': loading }">

                                    <span x-show="!loading">
                                        Learn More
                                    </span>

                                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                            </path>
                                        </svg>
                                    </span>

                                </x-button>
                            </div>
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
                class="flex flex-col md:flex-row items-stretch bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out overflow-hidden animate-on-scroll opacity-0 translate-y-10 min-h-[360px]">
                <div class="w-full md:w-[400px] h-64 md:h-[360px] flex">
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Experience Image"
                        class="object-cover w-full h-full" />
                </div>
                <div class="p-10 md:p-12 space-y-4 flex-1 flex flex-col justify-center">
                    <h5 class="text-2xl font-bold text-gray-900">Signature Experiences</h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Take your stay to the next level with our thoughtfully curated activities. Whether you're
                        looking to unwind, explore, or simply make the most of
                        your time, we offer a variety of choices to suit your mood and interests. Each
                        activity is designed to enrich your visit and create lasting memories.
                    </p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Activity 1</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Activity 2</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Activity 3</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Activity 4</span>
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div
                class="flex flex-col md:flex-row items-stretch bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out overflow-hidden animate-on-scroll opacity-0 translate-y-10 min-h-[360px]">
                <div class="p-10 md:p-12 space-y-4 order-2 md:order-1 flex-1 flex flex-col justify-center">
                    <h5 class="text-2xl font-bold text-gray-900">Memorable Moments, Made Here</h5>
                    <p class="text-gray-700 text-left leading-relaxed">
                        Host your next celebration in one of our versatile venues, perfect for various occasions. We offer a range of spaces to suit
                        your needs. Our team customizes packages based on your vision—just share your ideas, and
                        we'll handle the rest. Request a quote today and let’s bring your event to life!
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Event Type 1</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Event Type 2</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Event Type 3</span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full shadow-sm">Event Type 4</span>
                    </div>
                </div>
                <div class="w-full md:w-[400px] h-64 md:h-[360px] flex order-1 md:order-2">
                    <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Event Image"
                        class="object-cover w-full h-full" />
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="py-20 md:py-24 bg-gradient-to-b from-green-50 to-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 text-center">
            <div class="animate-on-scroll opacity-0 translate-y-10">
                <h2 class="text-green-700 font-bold tracking-wide uppercase mb-10 relative inline-block text-3xl">
                    WHAT OUR GUESTS SAY
                    <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        "Testimonial 1. This is a placeholder testimonial from a satisfied customer."
                    </p>
                    <p class="text-gray-900 font-bold">— Customer 1</p>
                </div>

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        "Testimonial 2. Another placeholder testimonial highlighting our services."
                    </p>
                    <p class="text-gray-900 font-bold">— Customer 2</p>
                </div>

                <div
                    class="bg-white p-8 md:p-10 rounded-3xl shadow-lg hover:shadow-2xl transition-transform duration-150 ease-out animate-on-scroll opacity-0 translate-y-10">
                    <i class="fa-solid fa-quote-left w-10 h-10 text-green-600 mb-4 mx-auto text-3xl"></i>
                    <p class="text-gray-700 italic leading-relaxed mb-6">
                        "Testimonial 3. A third placeholder testimonial for variety."
                    </p>
                    <p class="text-gray-900 font-bold">— Customer 3</p>
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
                class="text-green-700 font-bold text-3xl mb-10 tracking-wide animate-on-scroll opacity-0 translate-y-10">
                CONTACT US
                <span class="block mx-auto mt-2 w-16 h-1 bg-green-600 rounded-full"></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">
                <div
                    class="bg-gray-50 backdrop-blur-md p-10 md:p-12 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-300 ease-out animate-on-scroll opacity-0 translate-y-10 border border-gray-100">

                    <div class="lg:w-full px-2 mb-8 text-center">
                        <h2 class="text-green-700 font-extrabold text-3xl tracking-normal">Get in Touch</h2>
                        <p class="text-gray-500 text-sm mt-2">We'd love to hear from you!</p>
                    </div>

                    <form wire:submit.prevent="contactUs" class="space-y-6">

                        <div class="relative">
                            <input type="text" id="name" wire:model="name" required
                                class="peer w-full bg-white border border-gray-300 bg-transparent rounded-lg px-4 py-3 text-gray-900 placeholder-transparent focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Name" />
                            <label for="name"
                                class="absolute left-4 -top-2.5 bg-white rounded-sm px-1 text-xs text-green-600 transition-all
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5
                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                                Name
                            </label>
                        </div>

                        <!-- Email Input -->
                        <div class="relative">
                            <input type="email" id="email" wire:model="email" required
                                class="peer w-full bg-white border border-gray-300 bg-transparent rounded-lg px-4 py-3 text-gray-900 placeholder-transparent focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Email Address" />
                            <label for="email"
                                class="absolute left-4 -top-2.5 bg-white rounded-sm px-1 text-xs text-green-600 transition-all
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5
                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                                Email Address
                            </label>
                        </div>

                        <!-- Phone Input -->
                        <div class="relative">
                            <input type="tel" id="contact_number" wire:model="contact_number" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric"
                                maxlength="11"
                                class="peer w-full bg-white border border-gray-300 bg-transparent rounded-lg px-4 py-3 text-gray-900 placeholder-transparent focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="Phone Number" />
                            <label for="contact_number"
                                class="absolute left-4 -top-2.5 bg-white rounded-sm px-1 text-xs text-green-600 transition-all
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5
                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                                Phone Number
                            </label>
                        </div>

                        <!-- Message -->
                        <div class="relative">
                            <textarea id="message" wire:model="message" rows="4" required
                                class="peer w-full bg-white border border-gray-300 bg-transparent rounded-lg px-4 py-3 text-gray-900 placeholder-transparent focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none"
                                placeholder="Message"></textarea>
                            <label for="message"
                                class="absolute left-4 -top-2.5 bg-white rounded-sm px-1 text-xs text-green-600 transition-all
                       peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5
                       peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-green-600">
                                How can we help?
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <x-button
                                class="w-full py-4 text-lg font-bold shadow-lg hover:shadow-green-900/20 hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" wire:target="contactUs">

                                <span wire:loading.remove wire:target="contactUs">
                                    Send Message <i class="fas fa-paper-plane ml-1 text-sm"></i>
                                </span>

                                <span wire:loading wire:target="contactUs" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z">
                                        </path>
                                    </svg>
                                </span>
                            </x-button>
                        </div>

                    </form>
                </div>

                <div
                    class="overflow-hidden rounded-3xl shadow-lg h-full min-h-[450px] animate-on-scroll opacity-0 translate-y-10 relative bg-gray-100">
                    <iframe class="w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m12!1m8!1m3!1d123552.64394611071!2d121.0107556!3d14.5979292!3m2!1i1024!2i768!4f13.1!2m1!1sgoogle%20maps%20pup!5e0!3m2!1sen!2sph!4v1775720935885!5m2!1sen!2sph"
                        allowfullscreen loading="lazy"></iframe>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Scroll Animation --}}
<script>
    function initAnimations() {
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
    }

    document.addEventListener('DOMContentLoaded', initAnimations);

    document.addEventListener('livewire:navigated', initAnimations);
</script>
