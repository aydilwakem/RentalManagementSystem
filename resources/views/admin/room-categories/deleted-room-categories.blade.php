<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Deleted Room Categories') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800">
        @livewire('admin.room-categories.deleted-room-categories')
    </div>
</x-app-layout>
