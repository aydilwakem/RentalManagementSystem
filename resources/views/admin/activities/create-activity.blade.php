<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Activity') }}
        </h2>
    </x-slot>

    <div class="overflow-hidden sm:rounded-lg">
        @livewire('admin.activities.create-activity')
    </div>

</x-app-layout>
