<x-app-layout>

    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Backup') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Backups', 'url' => route('admin.view-backups')],
            ['label' => 'Create Backup', 'url' => route('admin.create-backup')],
        ]" />
    </x-slot>

    <div class="bg-white overflow-x-auto shadow-xl sm:rounded-lg dark:bg-gray-800 dark:text-white">
        @livewire('admin.backups.create-backup')
    </div>
</x-app-layout>
