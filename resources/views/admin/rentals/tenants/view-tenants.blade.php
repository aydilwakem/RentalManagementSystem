<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Tenants') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        @livewire('admin.tenants.view-tenants', ['lazy' => true])
    </div>

</x-app-layout>
