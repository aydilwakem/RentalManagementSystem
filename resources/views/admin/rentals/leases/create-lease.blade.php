<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Leases') }}
        </h2>
    </x-slot>

    <div class="overflow-hidden sm:rounded-lg">
        @livewire('admin.properties.leases.create-lease')
    </div>

</x-app-layout>
