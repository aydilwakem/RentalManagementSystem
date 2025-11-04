<x-guest-layout>

    <div class="min-h-screen bg-yellow-50 py-20 px-6 flex items-center justify-center">
        <div class="max-w-7xl w-full bg-white rounded-[32px] shadow-xl p-10 sm:p-16 relative overflow-hidden">
            <div class="flex flex-col lg:flex-row items-start gap-12">

                <!-- LEFT (The Canopy Section) -->
                <div class="flex-1 order-1 text-justify">
                    <!-- Header -->
                    <header class="text-center lg:text-left mb-10">
                        <img src="{{ asset('images/canopy-logo-alt (1).png') }}" alt="The Canopy Farm Logo"
                            class="h-24 w-24 mx-auto lg:mx-0 mb-6 object-contain drop-shadow-sm">
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-green-700 tracking-tight leading-tight">
                            The Canopy Farm
                        </h1>
                        <p class="mt-2 text-lg text-green-500 uppercase tracking-widest font-medium">
                            Rest • Beauty • Blessing
                        </p>
                        <p
                            class="mt-6 text-gray-700 text-base sm:text-lg italic leading-relaxed max-w-3xl mx-auto lg:mx-0">
                            “Then the Lord will create a cloud of smoke by day and a glowing flame of fire by night over
                            all
                            the glory; for there will be a CANOPY.” —
                            <span class="font-semibold text-green-700">Isaiah 4:5</span>
                        </p>
                    </header>

                    <!-- Intro / About -->
                    <section class="text-gray-700 leading-relaxed text-base sm:text-lg space-y-6">
                        <p>
                            <span class="font-semibold text-green-700">The Canopy Farm</span> was born from a vision of
                            rest,
                            beauty, and blessing — a sanctuary where people can pause from life’s busyness, reconnect
                            with
                            nature, commune in God’s presence, and bond with one another.
                        </p>

                        <p>
                            Just as the verse speaks of a canopy of protection and glory, we envisioned a place where
                            every
                            guest feels covered — by <span class="text-green-600 font-medium">peace, comfort, and
                                joy</span>.
                        </p>

                        <p>
                            Here at <span class="font-semibold text-green-700">The Canopy Farm,</span> we offer more
                            than
                            just accommodation and events. We create meaningful experiences from peaceful overnight
                            stays
                            under the stars to unforgettable celebrations like weddings, birthdays, and team gatherings.
                            Every moment here is designed to refresh your spirit and remind you of the simple joy of
                            being
                            surrounded by nature’s grace.
                        </p>
                    </section>
                </div>

                <!-- RIGHT (Cards Section) -->
                <div class="flex-1 order-2 flex flex-col gap-8">
                    <!-- Vision & Mission Cards -->
                    <section class="grid sm:grid-cols-2 lg:grid-cols-1 gap-6">
                        <div
                            class="rounded-2xl p-8 bg-green-50 border border-green-200 hover:shadow-lg transition transform hover:-translate-y-1 duration-300">
                            <h2 class="text-2xl font-bold text-green-700 mb-3">Our Vision</h2>
                            <p class="text-gray-700 leading-relaxed">
                                To be the Philippines’ premier nature-inspired destination, a place where people find
                                rest,
                                connection, and unforgettable recreation in the embrace of God's creation.
                            </p>
                        </div>

                        <div
                            class="rounded-2xl p-8 bg-green-50 border border-green-200 hover:shadow-lg transition transform hover:-translate-y-1 duration-300">
                            <h2 class="text-2xl font-bold text-green-700 mb-3">Our Mission</h2>
                            <p class="text-gray-700 leading-relaxed">
                                To provide warm hospitality, comfortable stays, and meaningful experiences that make
                                every
                                guest feel at home, inspired, refreshed, and renewed.
                            </p>
                        </div>
                    </section>

                    <!-- Divider -->
                    <div class="my-3 border-t border-green-200 w-2/3 mx-auto"></div>

                    <!-- Feedback + Developer Card -->
                    <section
                        class="rounded-2xl p-8 bg-gray-50 border border-gray-200 hover:shadow-lg transition transform hover:-translate-y-1 duration-300 text-center">
                        <div class="text-center">
                            <h3 class="text-md font-semibold text-green-700 mb-2 tracking-wide uppercase">Website
                                Developers
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed max-w-md mx-auto">
                                This website is created by <span class="font-medium text-green-700">Larabelles</span>, a
                                team of
                                dedicated
                                students from <span class="font-medium text-green-700">PUP - Taguig</span>, blending
                                creativity and technology to bring The Canopy Farm online experience to life. <br>

                                Help us grow and improve by sharing your thoughts in our short feedback form.
                                Your insights guide us as we continue to develop with purpose. <br>
                                <span class="font-medium text-green-700 py-2">Your Feedback Matters</span>
                            </p>
                            <x-button
                                href="https://docs.google.com/forms/d/e/1FAIpQLScfiqxpzjjMV3aZzSOK9UpYTCfpEp68p4qDsdyMxUmWS4kLvQ/viewform"
                                target="_blank" class="text-md font-semibold px-6 py-2 mt-3">
                                Share Your Feedback
                            </x-button>
                        </div>
                        <!-- Contact -->
                        <footer class="mt-6 border-t border-gray-200 pt-4 text-center">
                            <h3 class="text-md font-semibold text-green-700 mb-2">Contact Us</h3>
                            <p class="text-gray-600 text-sm">
                                For inquiries or suggestions, reach us at:
                            </p>
                            <p class="text-green-600 font-medium text-sm">
                                rmscapstone26@gmail.com
                            </p>
                        </footer>
                    </section>

                </div>
            </div>
        </div>
    </div>


</x-guest-layout>
