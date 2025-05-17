<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>huh</title>
</head>

<body>

    <x-guest-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-white-800 leading-tight">
                {{ __('Reserve a Room') }}
            </h2>
        </x-slot>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @livewire('guest.reservation-form')
        </div>
    </x-guest-layout>
</body>

</html>
