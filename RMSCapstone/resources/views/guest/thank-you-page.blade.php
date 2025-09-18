<x-guest-layout>
    <div class="py-14 flex items-center justify-center bg-yellow-50 px-4 ">
        <div class="bg-white rounded-3xl shadow-lg p-10 sm:py-12 max-w-2xl w-full text-center">
            <div class="flex flex-col items-center mb-6">
                <i class="fa-solid fa-circle-check text-green-700 text-5xl mb-4"></i>
                <h2 class="text-3xl font-bold text-green-700">Thank you for your reservation!</h2>
            </div>
            <p class="text-gray-600 mb-8 leading-relaxed text-justify">
                We look forward to accommodating you. Your payment receipt will be subject to verification. You will
                receive an email once it has been reviewed and your reservation is officially confirmed. Please check
                your inbox or spam folder, and stay tuned for updates regarding other important details.
            </p>

            <div class="flex flex-col items-center text-center">
                <x-button href="{{ route('guest.reservation-form') }}" class="w-48">
                    Make Another Reservation
                </x-button>

                <a href="{{ route('guest.homepage') }}"
                    class="mt-4 text-gray-600 hover:underline hover:font-medium hover:text-green-700">
                    Back to Home Page
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
