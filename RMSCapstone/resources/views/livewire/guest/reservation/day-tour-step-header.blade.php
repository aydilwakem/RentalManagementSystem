<div class="flex justify-center mb-8">
    <div class="flex items-center space-x-4">
        @foreach([1 => 'Select Tour', 2 => 'Guest Details', 3 => 'Review & Pay'] as $step => $label)
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $currentStep >= $step ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }}">
                    {{ $step }}
                </div>
                <span class="ml-2 {{ $currentStep >= $step ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                    {{ $label }}
                </span>
            </div>
            @if($step < 3)
                <div class="w-12 h-1 bg-gray-300"></div>
            @endif
        @endforeach
    </div>
</div>