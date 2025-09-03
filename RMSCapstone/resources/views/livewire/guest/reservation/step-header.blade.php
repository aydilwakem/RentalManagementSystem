@php
    $steps = [
        1 => 'Choose Room',
        2 => 'Choose Activity',
        3 => 'Guest Details',
        4 => 'Review',
    ];

    // Mobile view, 2 steps per page
    $page = ceil($currentStep / 2); // page 1 = steps 1–2, page 2 = steps 3–4
    $start = ($page - 1) * 2 + 1;
    $end = $start + 1;
@endphp

<!-- Mobile view -->
<div class="flex items-center justify-center space-x-6 my-6 md:hidden">
    @for ($i = $start; $i <= $end && $i <= count($steps); $i++)
        <div class="flex items-center space-x-2">
            <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
                {{ $currentStep > $i ? 'bg-green-500 text-white' : ($currentStep == $i ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                {!! $currentStep > $i ? '<i class="fas fa-check text-sm"></i>' : $i !!}
            </div>
            <span class="font-medium
                {{ $currentStep > $i ? 'text-green-600' : ($currentStep == $i ? 'text-yellow-600' : 'text-gray-700') }}">
                {{ $steps[$i] }}
            </span>
        </div>

        @if ($i < $end && $i < count($steps))
            <div class="h-px bg-gray-300 flex-1"></div>
        @endif
    @endfor
</div>

<!-- Desktop view -->
<div class="hidden md:flex items-center justify-center space-x-6 my-6 transition-all duration-300 ease-in-out">
    @foreach ($steps as $i => $label)
        <div class="flex items-center space-x-2">
            <div class="w-6 h-6 flex items-center justify-center rounded-full text-sm font-bold
                {{ $currentStep > $i ? 'bg-green-500 text-white' : ($currentStep == $i ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                {!! $currentStep > $i ? '<i class="fas fa-check text-sm"></i>' : $i !!}
            </div>
            <span class="font-medium
                {{ $currentStep > $i ? 'text-green-600' : ($currentStep == $i ? 'text-yellow-600' : 'text-gray-700') }}">
                {{ $label }}
            </span>
        </div>

        @if ($i < count($steps))
            <div class="h-px bg-gray-300 flex-1"></div>
        @endif
    @endforeach
</div>
