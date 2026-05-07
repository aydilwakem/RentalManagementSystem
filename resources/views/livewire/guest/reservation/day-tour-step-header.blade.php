@php
    $steps = [
        1 => 'Select Package',
        2 => 'Guest Details',
        3 => 'Review & Pay',
    ];
@endphp

<!-- Mobile view -->
<div class="flex items-center justify-center space-x-6 my-6 md:hidden">
    @foreach ($steps as $i => $label)
        <div class="flex items-center space-x-2">
            <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
                {{ $currentStep > $i ? 'bg-green-600 text-white' : ($currentStep == $i ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                {!! $currentStep > $i ? '<i class="fas fa-check text-xs"></i>' : $i !!}
            </div>
            <span class="font-medium text-sm
                {{ $currentStep > $i ? 'text-green-600' : ($currentStep == $i ? 'text-yellow-600' : 'text-gray-500') }}">
                {{ $label }}
            </span>
        </div>

        @if ($i < count($steps))
            <div class="h-px flex-1 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-300' }}"></div>
        @endif
    @endforeach
</div>

<!-- Desktop view -->
<div class="hidden md:flex items-center justify-center space-x-6 my-6 transition-all duration-300 ease-in-out">
    @foreach ($steps as $i => $label)
        <div class="flex items-center space-x-2">
            <div class="w-7 h-7 flex items-center justify-center rounded-full text-sm font-bold
                {{ $currentStep > $i ? 'bg-green-600 text-white' : ($currentStep == $i ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                {!! $currentStep > $i ? '<i class="fas fa-check text-xs"></i>' : $i !!}
            </div>
            <span class="font-medium text-base
                {{ $currentStep > $i ? 'text-green-600' : ($currentStep == $i ? 'text-yellow-600' : 'text-gray-500') }}">
                {{ $label }}
            </span>
        </div>

        @if ($i < count($steps))
            <div class="h-px flex-1 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-300' }}"></div>
        @endif
    @endforeach
</div>
