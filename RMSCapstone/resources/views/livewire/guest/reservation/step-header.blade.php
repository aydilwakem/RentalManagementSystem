<div class="flex items-center justify-center space-x-6 my-6 transition-all duration-300 ease-in-out">
    <!-- Step 1 -->
    <div class="flex items-center space-x-2">
        <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
            {{ $currentStep > 1 ? 'bg-green-500 text-white' : ($currentStep == 1 ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
            {!! $currentStep > 1 ? '<i class="fas fa-check text-sm"></i>' : '1' !!}
        </div>
        <span class="font-medium
            {{ $currentStep > 1 ? 'text-green-600' : ($currentStep == 1 ? 'text-yellow-600' : 'text-gray-700') }}">
            Choose Room
        </span>
    </div>

    <div class="h-px bg-gray-300 flex-1"></div>

    <!-- Step 2 -->
    <div class="flex items-center space-x-2">
        <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
            {{ $currentStep > 2 ? 'bg-green-500 text-white' : ($currentStep == 2 ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
            {!! $currentStep > 2 ? '<i class="fas fa-check text-sm"></i>' : '2' !!}
        </div>
        <span class="font-medium
            {{ $currentStep > 2 ? 'text-green-600' : ($currentStep == 2 ? 'text-yellow-600' : 'text-gray-700') }}">
            Choose Activity
        </span>
    </div>

    <div class="h-px bg-gray-300 flex-1"></div>

    <!-- Step 3 -->
    <div class="flex items-center space-x-2">
        <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
            {{ $currentStep > 3 ? 'bg-green-500 text-white' : ($currentStep == 3 ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
            {!! $currentStep > 3 ? '<i class="fas fa-check text-sm"></i>' : '3' !!}
        </div>
        <span class="font-medium
            {{ $currentStep > 3 ? 'text-green-600' : ($currentStep == 3 ? 'text-yellow-600' : 'text-gray-700') }}">
            Guest Details
        </span>
    </div>

    <div class="h-px bg-gray-300 flex-1"></div>

    <!-- Step 4 -->
    <div class="flex items-center space-x-2">
        <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
            {{ $currentStep == 4 ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
            4
        </div>
        <span class="font-medium
            {{ $currentStep == 4 ? 'text-green-600' : 'text-gray-700' }}">
            Review
        </span>
    </div>
</div>
