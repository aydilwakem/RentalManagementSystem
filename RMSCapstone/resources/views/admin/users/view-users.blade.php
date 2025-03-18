<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users & Roles') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <h3 class="font-semibold text-lg text-gray-700 p-4">{{ __('Manage Users') }}</h3>
        @livewire('admin.users.view-users')
    </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
        <h3 class="font-semibold text-lg text-gray-700 p-4">{{ __('Manage Roles') }}</h3>
        @livewire('admin.roles.view-roles')
    </div>

</x-app-layout>