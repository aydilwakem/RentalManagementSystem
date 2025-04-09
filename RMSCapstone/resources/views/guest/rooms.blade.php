<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Canopy Farm | Rooms</title>
</head>
<body>
    <x-guest-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rooms') }}
            </h2>
        </x-slot>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @livewire('guest.rooms')
        </div>
    </x-guest-layout>
</body>
</html>
