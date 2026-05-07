<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('No Access') }}
        </h2>
    </x-slot>

    <div class="overflow-hidden sm:rounded-lg">
        @livewire('no-access')
    </div>

</x-app-layout>
