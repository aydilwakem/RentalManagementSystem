<x-guest-layout>
    <div class="py-14 flex items-center justify-center bg-yellow-50 px-4 ">
        <div class="bg-white rounded-3xl shadow-lg p-10 sm:py-12 max-w-2xl w-full text-center">
            <div class="flex flex-col items-center mb-3">
                <i class="fa-solid fa-circle-check text-green-700 text-5xl mb-4"></i>
                <h2 class="text-3xl font-bold text-green-700">Thank you for your reservation!</h2>
            </div>
            <div class="p-5">
                <p class="text-gray-600 mb-8 leading-relaxed text-center">
                    Thank you for choosing Canopy Farm PH for your upcoming stay! We're delighted that you've chosen us for your retreat. You will receive an email once your reservation is officially
                    confirmed. <span class="text-green-700 font-semibold">Please check
                    your inbox or spam folder</span>, and stay tuned for updates regarding other important details.
                </p>
                <div class="flex justify-between items-center text-center px-16">
                    <x-button href="{{ route('guest.homepage') }}" target="_blank"
                        class="w-42 !bg-white !border-green-600 !border-2 !text-green-700 hover:!bg-green-700 hover:!text-white ease-in-out transition duration-150">
                        Back to Home
                    </x-button>

                    <x-button href="{{ route('guest.reservation-form') }}" class="w-42">
                        Make another reservation
                    </x-button>

                    {{-- <div class="flex justify-between items-center w-full px-20">
                        <a href="{{ route('guest.homepage') }}"
                            class="mt-4 text-gray-600 hover:underline hover:font-medium hover:text-green-700">
                            Back to Home Page
                        </a>

                        <a href="{{ route('guest.reservation-form') }}"
                            class="mt-4 text-gray-600 hover:underline hover:font-medium hover:text-green-700">
                            Make another reservation
                        </a>
                    </div> --}}
                </div>
            </div>

        </div>
        <div x-data="{ showModal: true, canClose: false, timer: 5 }" x-init="let interval = setInterval(() => {
            if (timer > 1) { timer--; } else {
                canClose = true;
                clearInterval(interval);
            }
        }, 1000);" x-show="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full text-center">
                <div
                    class="relative -mt-8 -mx-8 mb-6 bg-green-50 text-green-700 py-4 px-6 rounded-t-lg shadow-sm border-b">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-center">Thank you for your reservation!</h2>
                </div>
                <p class="mb-4 text-gray-700">This system is proudly developed by students from PUP-Taguig. As part of our academic requirements,
                    your feedback is very important to us. Kindly help by answering our short questionnaire. Thank you for your time and support!</p>

                <p class="mb-4 text-gray-700">
                    Please wait
                    <span x-text="timer"></span>
                    <span x-text="timer == 1 ? 'second' : 'seconds'"></span>
                    before closing this message.
                </p>



                <div class="flex items-center justify-between px-12">
                    <x-ghost-button x-bind:disabled="!canClose"
                        x-bind:class="!canClose ? 'opacity-50 cursor-not-allowed' : ''" x-on:click="showModal = false">
                        close
                    </x-ghost-button>
                    {{-- <button class="px-4 py-2 rounded bg-white text-green-700 border-green-700 border-2 font-semibold" x-bind:disabled="!canClose"
                        x-bind:class="!canClose ? 'opacity-50 cursor-not-allowed' : ''" x-on:click="showModal = false">
                        Close
                    </button> --}}
                    <x-button
                        href="https://docs.google.com/forms/d/e/1FAIpQLScfiqxpzjjMV3aZzSOK9UpYTCfpEp68p4qDsdyMxUmWS4kLvQ/viewform"
                        target="_blank">
                        Rate our website!
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
