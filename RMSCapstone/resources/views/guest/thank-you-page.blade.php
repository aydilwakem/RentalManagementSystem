<x-guest-layout>
    <div class="min-h-[490px] flex items-center justify-center bg-gray-100 px-4">
        <div class="bg-white rounded-3xl shadow-lg p-10 max-w-2xl w-full text-center">
            <div class="flex flex-col items-center mb-6">
                <i class="fa-solid fa-circle-check text-green-700 text-5xl mb-4"></i>
                <h2 class="text-3xl font-bold text-green-700">Thank you for your reservation!</h2>
            </div>
            <p class="text-gray-600 mb-8 leading-relaxed text-justify">
                We look forward to accommodating you. Your payment receipt will be subject to verification. You will
                receive an email once it has been reviewed and your reservation is officially confirmed. Please check your inbox or spam folder, and stay tuned for updates regarding other important details.
            </p>

            <x-button href="{{ route('guest.homepage') }}">
                Back to Home Page
            </x-button>
        </div>
    </div>
</x-guest-layout>
