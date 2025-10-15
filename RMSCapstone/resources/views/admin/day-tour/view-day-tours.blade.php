<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Day Tour') }}
        </h2>
    </x-slot>

    <div class="overflow-hidden sm:rounded-lg">
        @livewire('admin.day-tours.view-day-tours')
    </div>

</x-app-layout>
