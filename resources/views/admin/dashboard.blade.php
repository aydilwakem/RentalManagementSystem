<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">
</head>

<body>
    <x-app-layout>
        <div class="py-18">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl p-6">
                    @livewire('admin.dashboard')
                </div>
            </div>
        </div>
    </x-app-layout>

</body>

</html>
