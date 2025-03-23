<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a Reservation') }}
        </h2>
    </x-slot>

    @livewire('admin.transactions.new-transaction.create-transaction')

</x-app-layout>