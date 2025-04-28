<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Activities') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-[#2A2A2A]  overflow-hidden shadow-xl sm:rounded-lg">
        @livewire('admin.activities.view-activities')
    </div>
</x-app-layout>
