<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">


    <!-- Scripts -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="h-screen font-sans antialiased">

    <x-banner />

    <div class="h-screen flex bg-gray-100"
        x-data="{ sidebarWidth: 256 }"
        x-init="$watch('sidebarWidth', value => document.documentElement.style.setProperty('--sidebar-width', `${value}px`))">

        <!-- Sidebar -->
        <livewire:sidebar
            x-ref="sidebar"
            x-on:resize.window="sidebarWidth = $refs.sidebar.offsetWidth"
            class="fixed left-0 top-0 bottom-0 w-[var(--sidebar-width)] h-full flex flex-col bg-white shadow-lg" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-[var(--sidebar-width)] transition-all duration-300">

            <!-- Page Heading -->
            {{-- @livewire('navigation-menu') --}}

            @if (isset($header))
                <header class="bg-white shadow w-full px-6">
                    <div class="py-6">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="p-6 flex-1 overflow-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
