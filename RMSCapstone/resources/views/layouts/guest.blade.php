<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Canopy Farm') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/canopy-logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script> --}}

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- Alpine Core -->
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

</head>

<body x-data="themeToggle()" x-init="init()" class="font-sans antialiased transition duration-300">
    {{-- @if (isset($header))
    <header class="bg-white white:bg-[#2A2A2A] shadow w-full px-6">
        <div class="py-6">
            {{ $header }}
        </div>
    </header>
    @endif --}}
    @livewire('guest.navbar')

    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>

    @livewire('guest.footer')

    @livewireScripts

    <script>
        function themeManager() {
            return {
                colorTheme: localStorage.getItem('colorTheme') || 'root', // default color theme
                // Initialize darkMode from localStorage, defaulting to 'system'
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
