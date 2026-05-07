<div class="flex items-stretch gap-4 mb-8">
    <!-- Image  -->
    <div class="flex-shrink-0">
        <img src="{{ asset('images/Welcome1.svg') }}" alt="My Icon" class="w-full h-auto rounded-lg">
    </div>

    <!-- Welcome Text  -->
    <div class="flex-1 bg-gray-100 rounded-lg p-4 flex flex-col justify-center items-center text-center">
        <h1 class="text-3xl font-bold text-green-700 mb-4">
            Hello, {{ $first_name }} {{ $last_name }}!
        </h1>
        <h2 class="text-lg font-semibold text-green-700 mb-2">Explore Canopy</h2>
        <p class="text-gray-600 mb-3">
            Discover all the features and benefits our platform offers.
        </p>
        <x-button href="{{ route('guest.homepage') }}" class="w-40 flex justify-center text-center">
            Get Started
        </x-button>
    </div>

</div>
