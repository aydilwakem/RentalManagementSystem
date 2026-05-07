@props(['type' => 'success'])

@php
    $styles = [
        'success' => [
            'iconColor' => 'text-green-500',
            'bgColor' => 'bg-green-100',
            'svg' => '<path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16
                      8 8 0 0 0 0 16Zm3.707-9.707a1 1
                      0 0 0-1.414-1.414L9 10.586
                      7.707 9.293a1 1 0 1 0-1.414
                      1.414l2 2a1 1 0 0 0 1.414
                      0l4-4Z" clip-rule="evenodd" />',
        ],
        'danger' => [
            'iconColor' => 'text-red-500',
            'bgColor' => 'bg-red-100',
            'svg' => '<path d="M10 .5a9.5 9.5 0 1 0 9.5
                      9.5A9.51 9.51 0 0 0 10 .5Zm3.707
                      11.793a1 1 0 1 1-1.414 1.414L10
                      11.414l-2.293 2.293a1 1 0 0
                      1-1.414-1.414L8.586 10 6.293
                      7.707a1 1 0 0 1 1.414-1.414L10
                      8.586l2.293-2.293a1 1 0 0 1
                      1.414 1.414L11.414 10l2.293
                      2.293Z" />',
        ],
    ];

    $style = $styles[$type] ?? $styles['success'];
@endphp

<div id="toast-success"
     x-data="{ show: true }"
     x-show="show"
     class="fixed top-4 left-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm"
     role="alert">

    <!-- Icon -->
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
        <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.707a1 1
                0 0 0-1.414-1.414L9 10.586
                7.707 9.293a1 1 0 1 0-1.414
                1.414l2 2a1 1 0 0 0 1.414
                0l4-4Z" clip-rule="evenodd" />
        </svg>
        <span class="sr-only">Success icon</span>
    </div>

    <!-- Content -->
    <div class="ms-3 text-sm font-normal">{{ $slot }}</div>

    <!-- Close button -->
    <button type="button" @click="show = false"
        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900
               rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100
               inline-flex items-center justify-center h-8 w-8">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
    </button>
</div>
