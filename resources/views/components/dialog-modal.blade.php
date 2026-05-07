@props([
    'id' => null,
    'maxWidth' => 'lg',
    'type' => 'default', // default (success), danger (danger), warning (yellow), ghost (gray), info (blue)
    'title' => null,
    'content' => null,
    'footer' => null,
])

@php
    $typeConfig = [
        'ghost' => [
            'modalBg' => 'bg-white',
            'iconBg' => 'bg-gray-100',
            'iconColor' => 'text-gray-500',
            'titleColor' => 'text-gray-900',
            'borderColor' => 'border-gray-300',
            'buttonColor' => 'bg-indigo-600 hover:bg-indigo-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z" />' // Info icon as a default
        ],
        'danger' => [
            'modalBg' => 'bg-white',
            'iconBg' => 'bg-red-100',
            'iconColor' => 'text-red-600',
            'titleColor' => 'text-red-800',
            'borderColor' => 'border-red-300',
            'buttonColor' => 'bg-red-600 hover:bg-red-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />'
        ],
        'warning' => [
            'modalBg' => 'bg-white',
            'iconBg' => 'bg-yellow-100',
            'iconColor' => 'text-yellow-600',
            'titleColor' => 'text-yellow-800',
            'borderColor' => 'border-yellow-300',
            'buttonColor' => 'bg-yellow-600 hover:bg-yellow-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 16c-.77 1.333.192 3 1.732 3z" />' // Exclamation triangle icon
        ],
        'default' => [
            'modalBg' => 'bg-white',
            'iconBg' => 'bg-green-100',
            'iconColor' => 'text-green-600',
            'titleColor' => 'text-green-800',
            'borderColor' => 'border-green-300',
            'buttonColor' => 'bg-green-600 hover:bg-green-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
        ],
        'info' => [
            'modalBg' => 'bg-white',
            'iconBg' => 'bg-blue-100',
            'iconColor' => 'text-blue-600',
            'titleColor' => 'text-blue-800',
            'borderColor' => 'border-blue-300',
            'buttonColor' => 'bg-blue-600 hover:bg-blue-700',
            'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z" />'
        ],
    ];

    $config = $typeConfig[$type] ?? $typeConfig['default'];
@endphp

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes->merge(['class' => 'rounded-lg shadow-xl']) }}>
    <div class="p-6 sm:p-8 text-center relative">
        {{-- Close button --}}
        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full" @click="show = false">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Icon --}}
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $config['iconBg'] }}">
            <svg class="h-6 w-6 {{ $config['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                {!! $config['iconPath'] !!}
            </svg>
        </div>

        {{-- Title --}}
        <h3 class="mt-5 text-xl leading-6 font-medium {{ $config['titleColor'] }}">
            {{ $title }}
        </h3>

        {{-- Content --}}
        <div class="mt-2 mb-4 text-md text-gray-500">
            {{ $content }}
        </div>
    </div>

    {{-- Footer --}}
    <div class="px-6 py-4 bg-gray-100 rounded-b-lg flex justify-between space-x-3">
        {{ $footer }}
    </div>
</x-modal>
