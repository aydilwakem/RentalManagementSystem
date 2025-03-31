<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Branding') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        @livewire('admin.settings.branding.view-branding')
    </div>

</x-app-layout>