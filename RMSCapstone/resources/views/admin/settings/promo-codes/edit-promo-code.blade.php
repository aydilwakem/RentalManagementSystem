<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Promo Code') }}
        </h2>
    </x-slot>

    <div class=" overflow-hidden sm:rounded-lg">
        @livewire('admin.settings.promo-codes.edit-promo-code')
    </div>

</x-app-layout>