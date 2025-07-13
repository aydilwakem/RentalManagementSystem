<x-app-layout>
    <div>
        <!-- Header -->
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-surface leading-tight dark:text-white">
                {{ __('Brand Appearance') }}
            </h2>
        </x-slot>

        <!-- Body Container -->
        <div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

                <div class="relative flex items-center">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Customize Brand Appearance</h2>

                    <!-- Back Button -->
                    <button onclick="history.back()"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                        <span class="leading-none translate-y-[-3px]">&times;</span>
                    </button>
                </div>

                <!-- Form container -->

                <!-- Wrap in Alpine for state management -->
                <div x-data="{ darkMode: false, fontSize: 'text-base' }" :class="fontSize"
                    class="mx-4 sm:mx-auto rounded-2xl">

                    <form wire:submit.prevent="">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                            <!-- Color Picker -->
                            <div class="space-y-2 col-span-3">
                                <label for="hs-color-input" class="block text-sm font-medium mb-2 dark:text-white">Theme
                                    Selector</label>

                                <!-- Theme Selector -->
                                <div class="p-6 rounded-lg border border-text-secondary  mb-8 ">
                                    <div class="flex flex-wrap gap-3">
                                        <button @click="theme = 'root'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Green Theme
                                        </button>
                                        <button @click="theme = 'theme-rose'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Rose Theme
                                        </button>
                                        <button @click="theme = 'theme-blue'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Blue Theme
                                        </button>
                                        <button @click="theme = 'theme-purple'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Purple Theme
                                        </button>
                                        <button @click="theme = 'theme-red'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Red Theme
                                        </button>
                                        <button @click="theme = 'theme-yellow'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Yellow Theme
                                        </button>
                                        <button @click="theme = 'theme-black'; localStorage.setItem('theme', theme)"
                                            class="bg-primary text-black px-4 py-2 rounded hover:bg-primary-600 transition-colors">
                                            Black Theme
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Theme Toggle -->
                            <div class="sm:col-span-2 space-y-2">
                                <div x-data="themeToggle()" x-init="init()" class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Theme
                                        Mode</label>

                                    <div class="flex items-center gap-4">
                                        <!-- Dark Mode -->
                                        <button @click="setTheme('dark')"
                                            :class="theme === 'dark' ? 'ring-2 ring-blue-500' : ''"
                                            class="rounded-xl border w-28 h-20 overflow-hidden focus:outline-none transition-all flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-10"
                                                viewBox="0 0 100 60">
                                                <rect width="100" height="60" rx="8" fill="#1f2937" />
                                                <rect x="10" y="15" width="20" height="8" rx="2"
                                                    fill="#374151" />
                                                <rect x="35" y="15" width="25" height="8" rx="2"
                                                    fill="#374151" />
                                                <rect x="65" y="15" width="20" height="8" rx="2"
                                                    fill="#374151" />
                                                <rect x="10" y="30" width="80" height="20" rx="4"
                                                    fill="#374151" />
                                                <circle cx="90" cy="10" r="3" fill="#10b981" />
                                                <circle cx="80" cy="10" r="3" fill="#ef4444" />
                                            </svg>
                                            <p class="text-center text-sm mt-1">Dark</p>
                                        </button>

                                        <!-- System Default -->
                                        <button @click="setTheme('system')"
                                            :class="theme === 'system' ? 'ring-2 ring-blue-500' : ''"
                                            class="rounded-xl border w-28 h-20 overflow-hidden focus:outline-none transition-all flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-10"
                                                viewBox="0 0 100 60">
                                                <defs>
                                                    <linearGradient id="split" x1="0" y1="0"
                                                        x2="100%" y2="0">
                                                        <stop offset="50%" stop-color="#ffffff" />
                                                        <stop offset="50%" stop-color="#1f2937" />
                                                    </linearGradient>
                                                </defs>
                                                <rect width="100" height="60" rx="8" fill="url(#split)" />
                                                <!-- Window items -->
                                                <rect x="10" y="15" width="20" height="8" rx="2"
                                                    fill="#9ca3af" />
                                                <rect x="35" y="15" width="25" height="8" rx="2"
                                                    fill="#9ca3af" />
                                                <rect x="65" y="15" width="20" height="8" rx="2"
                                                    fill="#9ca3af" />
                                                <rect x="10" y="30" width="80" height="20" rx="4"
                                                    fill="#6b7280" />
                                                <circle cx="90" cy="10" r="3" fill="#10b981" />
                                                <circle cx="80" cy="10" r="3" fill="#ef4444" />
                                            </svg>
                                            <p class="text-center text-sm mt-1">System Default</p>
                                        </button>

                                        <!-- Light Mode -->
                                        <button @click="setTheme('light')"
                                            :class="theme === 'light' ? 'ring-2 ring-blue-500' : ''"
                                            class="rounded-xl border w-28 h-20 overflow-hidden focus:outline-none transition-all flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-10"
                                                viewBox="0 0 100 60">
                                                <rect width="100" height="60" rx="8" fill="#ffffff" />
                                                <rect x="10" y="15" width="20" height="8" rx="2"
                                                    fill="#d1d5db" />
                                                <rect x="35" y="15" width="25" height="8" rx="2"
                                                    fill="#d1d5db" />
                                                <rect x="65" y="15" width="20" height="8" rx="2"
                                                    fill="#d1d5db" />
                                                <rect x="10" y="30" width="80" height="20" rx="4"
                                                    fill="#e5e7eb" />
                                                <circle cx="90" cy="10" r="3" fill="#10b981" />
                                                <circle cx="80" cy="10" r="3" fill="#ef4444" />
                                            </svg>
                                            <p class="text-center text-sm mt-1 text-blue-600">Light</p>
                                        </button>

                                    </div>
                                </div>
                            </div>


                            <!-- Font Size Selection -->
                            <div class="sm:col-span-2 space-y-2">
                                <label class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Font
                                    Size</label>
                                <div class="flex items-center gap-4">
                                    <button type="button" @click="fontSize = 'text-sm'"
                                        :class="{ 'ring-2 ring-blue-500': fontSize === 'text-sm' }"
                                        class="px-4 py-2 border rounded-lg text-sm hover:scale-105 transition">
                                        Small
                                    </button>

                                    <button type="button" @click="fontSize = 'text-base'"
                                        :class="{ 'ring-2 ring-blue-500': fontSize === 'text-base' }"
                                        class="px-4 py-2 border rounded-lg text-base hover:scale-105 transition">
                                        Default
                                    </button>

                                    <button type="button" @click="fontSize = 'text-lg'"
                                        :class="{ 'ring-2 ring-blue-500': fontSize === 'text-lg' }"
                                        class="px-4 py-2 border rounded-lg text-lg hover:scale-105 transition">
                                        Large
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between items-center space-y-2 mt-6">
                            <x-button href="{{ route('dashboard') }}" type="button"
                                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                                Cancel
                            </x-button>
                            <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                                wire:click="confirmCreate">
                                Save Changes
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function themeToggle() {
            return {
                theme: localStorage.getItem('theme') || 'system',

                init() {
                    this.applyTheme(this.theme);
                },

                setTheme(value) {
                    this.theme = value;
                    localStorage.setItem('theme', value);
                    this.applyTheme(value);
                },

                applyTheme(value) {
                    const html = document.documentElement;
                    if (value === 'dark') {
                        html.classList.add('dark');
                    } else if (value === 'light') {
                        html.classList.remove('dark');
                    } else if (value === 'system') {
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        prefersDark ? html.classList.add('dark') : html.classList.remove('dark');
                    }
                }
            };
        }
    </script>

</x-app-layout>
