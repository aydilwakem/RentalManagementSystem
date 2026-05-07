<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Backups') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-x-auto shadow-xl sm:rounded-lg dark:bg-gray-800 dark:text-white">
        @livewire('admin.backups.view-backups', ['lazy' => true])
    </div>
</x-app-layout>
