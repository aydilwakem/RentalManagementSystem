<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users & Roles') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        @livewire('admin.users.view-users')
    </div>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
        @livewire('admin.roles.view-roles')
    </div>

</x-app-layout>
