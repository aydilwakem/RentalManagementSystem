<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proof of Payment Page') }}
        </h2>
    </x-slot>

    <div class="bg-yellow-50 overflow-hidden shadow-xl sm:rounded-lg">
        @livewire('guest.proof-of-payment-page')
    </div>
</x-guest-layout>
