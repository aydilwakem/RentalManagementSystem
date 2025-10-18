<x-guest-layout>
    <section class="min-h-screen bg-yellow-50 py-20 px-6 flex items-center justify-center">
        <div class="max-w-3xl w-full bg-white rounded-[32px] shadow-xl p-10 sm:p-16 relative overflow-hidden">

            <!-- Header -->
            <header class="text-center mb-14">
                <img src="{{ asset('images/Larabelles (5).png') }}" alt="The Canopy Farm Logo"
                    class="h-24 w-24 mx-auto mb-6 object-contain drop-shadow-sm">
                <h1 class="text-5xl sm:text-6xl font-extrabold text-green-700 tracking-tight leading-tight">
                    The Canopy Farm
                </h1>
                <p class="mt-3 text-lg text-green-500 uppercase tracking-widest font-medium">
                    Rest • Beauty • Blessing
                </p>
                <p class="mt-6 text-gray-700 text-base sm:text-lg leading-relaxed max-w-3xl mx-auto italic">
                    “Then the Lord will create a cloud of smoke by day and a glowing flame of fire by night over all the glory;
                    for there will be a CANOPY.” — <span class="font-semibold text-green-700">Isaiah 4:5</span>
                </p>
            </header>

            <!-- Intro / About -->
            <section class="text-gray-700 leading-relaxed text-base sm:text-lg space-y-6">
                <p>
                    <span class="font-semibold text-green-700">The Canopy Farm</span> was born from a vision of rest, beauty, and blessing —
                    a sanctuary where people can pause from life’s busyness, reconnect with nature, commune in God’s presence,
                    and bond with one another.
                </p>

                <p>
                    Just as the verse speaks of a canopy of protection and glory, we envisioned a place where every guest
                    feels covered — by <span class="text-green-600 font-medium">peace, comfort, and joy</span>.
                </p>

                <p>
                    More than just a venue, <span class="font-semibold text-green-700">The Canopy Farm</span> is a story —
                    a living expression of simplicity, renewal, and grace. From peaceful overnight stays under the stars
                    to life’s most cherished celebrations, every experience is thoughtfully designed to refresh your spirit
                    and bring people closer together.
                </p>
            </section>

            <!-- Vision / Mission -->
            <section class="mt-16 grid md:grid-cols-2 gap-8">
                <div class="rounded-2xl p-8 bg-green-50 border border-green-200 hover:shadow-md transition">
                    <h2 class="text-2xl font-bold text-green-700 mb-3">Our Vision</h2>
                    <p class="text-gray-700 leading-relaxed">
                        To be the Philippines’ premier nature-inspired destination — a place where people find rest, connection,
                        and unforgettable recreation in the embrace of God's creation.
                    </p>
                </div>

                <div class="rounded-2xl p-8 bg-green-50 border border-green-200 hover:shadow-md transition">
                    <h2 class="text-2xl font-bold text-green-700 mb-3">Our Mission</h2>
                    <p class="text-gray-700 leading-relaxed">
                        To provide warm hospitality, comfortable stays, and meaningful experiences that make every guest feel
                        at home — inspired, refreshed, and renewed.
                    </p>
                </div>
            </section>

            <!-- Developer Section -->
            <section class="mt-20 text-center">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-xl font-semibold text-green-600 mb-3 tracking-wide uppercase">
                        About the Website Developers
                    </h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        This website was created by <span class="font-medium text-green-700">Larabelles</span>,
                        a group of dedicated students from the
                        <span class="font-medium text-green-700">Polytechnic University of the Philippines – Taguig</span>.
                    </p>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed mt-3">
                        Built as part of their academic journey, this project reflects a shared mission to blend creativity,
                        technology, and faith — bringing people closer to nature through innovation and design.
                    </p>
                </div>
            </section>

            <!-- Feedback -->
            <section class="mt-16 text-center">
                <div class="max-w-2xl mx-auto">
                    <h2 class="text-xl font-semibold text-green-700 mb-3 tracking-wide uppercase">Your Feedback Matters</h2>
                    <p class="text-gray-600 mb-8 text-sm sm:text-base">
                        Help us grow and improve by sharing your thoughts in our short feedback form.
                        Your insights guide us as we continue to develop with purpose.
                    </p>
                    <x-button
                        href="https://docs.google.com/forms/d/e/1FAIpQLScfiqxpzjjMV3aZzSOK9UpYTCfpEp68p4qDsdyMxUmWS4kLvQ/viewform"
                        target="_blank"
                        class=" text-lg font-semibold px-8 py-3 ">
                        Share Your Feedback
                    </x-button>
                </div>
            </section>

            <!-- Contact -->
            <footer class="mt-16 border-t border-gray-200 pt-8 text-center">
                <h3 class="text-xl font-semibold text-green-700 mb-2">Contact Us</h3>
                <p class="text-gray-600 text-base">
                    For inquiries or suggestions, you may reach us at:
                </p>
                <p class=" text-green-600 font-medium text-md">
                    rmscapstone26@gmail.com
                </p>
            </footer>
        </div>
    </section>
</x-guest-layout>
