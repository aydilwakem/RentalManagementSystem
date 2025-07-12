<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('View Leases') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        @livewire('admin.properties.leases.view-leases', ['lazy' => true])
    </div>

</x-app-layout>
