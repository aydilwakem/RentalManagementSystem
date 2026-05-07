<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Day Tour Packages') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800 dark:text-white">
        @livewire('admin.day-tours.view-day-tours')
    </div>

</x-app-layout>
