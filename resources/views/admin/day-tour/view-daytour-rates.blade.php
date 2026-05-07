<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Day Tour Rates') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800 dark:text-white">
        @livewire('admin.day-tour-rates.view-day-tour-rates')
    </div>

</x-app-layout>
