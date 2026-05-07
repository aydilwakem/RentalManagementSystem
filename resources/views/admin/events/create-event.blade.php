<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Event') }}
        </h2>
    </x-slot>

    <div class=" overflow-hidden sm:rounded-lg">
        @livewire('admin.events.create-event')
    </div>

</x-app-layout>
