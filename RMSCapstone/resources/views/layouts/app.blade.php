<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Canopy Farm') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

    <!-- Calendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Phone Number Dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.10.5/build/js/intlTelInput.min.js"></script>


    <!-- Text Editor -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

</head>

<body x-data="themeToggle()" x-init="init()" class="transition duration-300">

    <x-banner />

    <div class="h-screen rounded-2xl flex bg-gray-100 dark:bg-gray-900 dark:rounded-none" x-data="{ sidebarWidth: 256 }"
        x-init="$watch('sidebarWidth', value => document.documentElement.style.setProperty('--sidebar-width', `${value}px`))">

        <!-- Sidebar -->
        <livewire:sidebar x-ref="sidebar" x-on:resize.window="sidebarWidth = $refs.sidebar.offsetWidth"
            class="fixed left-0 top-0 bottom-0 w-[var(--sidebar-width)] h-full flex flex-col bg-white shadow-lg" />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-[var(--sidebar-width)] transition-all duration-300 min-w-0">

            <!-- Page Heading -->
            {{-- @livewire('navigation-menu') --}}

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow w-full px-6">
                    <div class="mt-4 mb-4">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content
                <main class="p-6 flex-1 overflow-auto bg-gray-100">

                    {{-- @livewire('web-controller')
                    <button @click="darkMode = !darkMode">
                        Toggle Dark Mode
                    </button> --}}
                </main>
            -->
            <main class="flex-1 overflow-auto px-6 py-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    @stack('modals')

    @livewireScripts

    <script>
        function themeManager() {
            return {
                colorTheme: localStorage.getItem('colorTheme') || 'root', // default color theme
                // Initialize darkMode from localStorage, defaulting to 'light'
                currentDarkModeSetting: localStorage.getItem('darkMode') || 'light',

                init() {
                    this.applyColorTheme();
                    this.applyDarkMode(); // Apply on init

                    // Watch for system preference changes
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                        if (this.currentDarkModeSetting === 'system') {
                            this.applyDarkMode(); // Re-apply if system mode is active
                        }
                    });

                    // Watch for changes to currentDarkModeSetting from within Alpine
                    this.$watch('currentDarkModeSetting', () => {
                        this.applyDarkMode();
                    });
                },

                // Set and apply theme color
                setColorTheme(theme) {
                    this.colorTheme = theme;
                    localStorage.setItem('colorTheme', theme);
                    this.applyColorTheme();
                },

                applyColorTheme() {
                    const html = document.documentElement;
                    // Remove old color classes
                    html.classList.remove('root', 'theme-rose', 'theme-blue', 'theme-purple', 'theme-red', 'theme-yellow',
                        'theme-black');
                    html.classList.add(this.colorTheme);
                },

                // Set the dark mode preference (dark or light)
                setDarkMode(mode) {
                    this.currentDarkModeSetting = mode;
                    localStorage.setItem('darkMode', mode);
                    // The $watch 'currentDarkModeSetting' will call applyDarkMode()
                },

                // Apply the 'dark' class to the html element based on the current setting
                applyDarkMode() {
                    const html = document.documentElement;
                    if (this.currentDarkModeSetting === 'dark' ||
                        (this.currentDarkModeSetting === 'system' && window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                },

                // Helper to check the active dark mode setting for button styling
                isMode(mode) {
                    return this.currentDarkModeSetting === mode;
                },

                isColor(theme) {
                    return this.colorTheme === theme;
                }
            };
        }

        // Initialize the theme manager globally if it's not already on the body
        // This ensures it runs even if the body's x-data isn't the first to load.
        document.addEventListener('alpine:init', () => {
            Alpine.data('themeToggle',
                themeManager); // Connects themeToggle component from the HTML to the manager.
        });
    </script>

</body>

</html>
