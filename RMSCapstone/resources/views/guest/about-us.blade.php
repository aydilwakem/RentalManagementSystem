<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-yellow-50 px-4">
        <div class="bg-white rounded-3xl shadow-lg p-8 sm:p-10 max-w-lg w-full text-center">

            <!-- Icon / Header -->
            <div class="flex flex-col items-center mb-4">
                <img src="{{ asset('images/Larabelles (5).png') }}" class="h-24 w-24 mb-4">
                {{-- <i class="fa-solid fa-circle-info text-green-700 text-5xl mb-4"></i> --}}
                <h1 class="text-2xl sm:text-3xl font-bold text-green-700 mb-4">
                    About This Project
                </h1>
                <!-- Message -->
                <p class="text-gray-700 leading-relaxed text-sm sm:text-base">
                    Welcome to the Rental Management System! This website is proudly developed by Larabelles, a group of
                    students from
                    <span class="font-semibold text-green-700">Polytechnic University of the Philippines – Taguig</span>.
                    It serves as part of our academic requirements.
                    Your feedback is very important to help us improve this project and fulfill our course requirements.
                </p>
            </div>


            <!-- Call to Action -->
            <p class="text-gray-600 mb-6 text-sm sm:text-base">
                If you’d like to help us, kindly answer a short questionnaire.
                We sincerely appreciate your time and support.
            </p>

            <!-- Button -->
            <div class="mb-6">
                <x-button
                    href="https://docs.google.com/forms/d/e/1FAIpQLScfiqxpzjjMV3aZzSOK9UpYTCfpEp68p4qDsdyMxUmWS4kLvQ/viewform"
                    target="_blank" class="px-6 py-3 text-lg font-semibold">
                    Answer Feedback Form
                </x-button>
            </div>

            <!-- Contact Us -->
            <div class="border-t pt-4">
                <h2 class="text-md font-semibold text-green-700 mb-2">Contact Us</h2>
                <p class="text-gray-600 text-xs sm:text-base">
                    For questions, concerns, or suggestions, you may reach us at:
                </p>

                <span class="text-green-700 font-medium">
                    rmscapstone26@gmail.com
                </span>
            </div>
        </div>
    </div>
</x-guest-layout>
