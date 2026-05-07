@props(['id' => null, 'maxWidth' => 'lg'])


<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes->merge(['class' => 'rounded-lg shadow-xl']) }}>

    {{-- Header --}}
    <div class="px-6 py-3 bg-green-100 rounded-t-lg border-b">
        <h2 class="text-2xl font-bold text-center text-green-700">
            {{ $title }}
        </h2>
        {{-- Close button --}}
        <button type="button"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full"
            @click="show = false">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="mb-4 text-md text-gray-700 px-6 py-2">
        {{ $content }}
    </div>

    {{-- Footer --}}
    <div class="px-6 py-4 bg-gray-100 rounded-b-lg flex justify-between items-center gap-2 mt-6">
        {{ $footer }}
    </div>
</x-modal>
