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
            <div
                class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

                <div class="relative flex items-center">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Customize Brand
                        Appearance</h2>

                    <!-- Back Button -->
                    <button onclick="history.back()"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                        <span class="leading-none translate-y-[-3px]">&times;</span>
                    </button>
                </div>

                <!-- Form container -->

                <!-- Wrap in Alpine for state management -->
                <div x-data="{ darkMode: false, fontSize: 'text-base' }" :class="fontSize" class="mx-4 sm:mx-auto rounded-2xl">

                    <form wire:submit.prevent="">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                            <!-- Color Picker -->
                            <div class="space-y-2 col-span-3">
                                <label for="hs-color-input" class="block text-sm font-medium mb-2 dark:text-white">Theme
                                    Selector</label>

                                <!-- Theme Selector -->
                                <div class="p-6 rounded-lg border border-text-secondary mb-8">
                                    <div class="flex flex-wrap gap-3">
                                        <!-- Green Theme -->
                                        <button @click="setColorTheme('root')"
                                            :class="isColor('root') ? 'ring-2 ring-green-500 bg-green-500 text-white' :
                                                'bg-white text-black hover:bg-green-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Green Theme
                                        </button>

                                        <!-- Rose Theme -->
                                        <button @click="setColorTheme('theme-rose')"
                                            :class="isColor('theme-rose') ? 'ring-2 ring-rose-500 bg-rose-500 text-white' :
                                                'bg-white text-black hover:bg-rose-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Rose Theme
                                        </button>

                                        <!-- Blue Theme -->
                                        <button @click="setColorTheme('theme-blue')"
                                            :class="isColor('theme-blue') ?
                                                'ring-2 ring-blue-500 bg-blue-500 text-white' :
                                                'bg-white text-black hover:bg-blue-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Blue Theme
                                        </button>

                                        <!-- Purple Theme -->
                                        <button @click="setColorTheme('theme-purple')"
                                            :class="isColor('theme-purple') ?
                                                'ring-2 ring-purple-500 bg-purple-500 text-white' :
                                                'bg-white text-black hover:bg-purple-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Purple Theme
                                        </button>

                                        <!-- Red Theme -->
                                        <button @click="setColorTheme('theme-red')"
                                            :class="isColor('theme-red') ?
                                                'ring-2 ring-red-500 bg-red-500 text-white' :
                                                'bg-white text-black hover:bg-red-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Red Theme
                                        </button>

                                        <!-- Yellow Theme -->
                                        <button @click="setColorTheme('theme-yellow')"
                                            :class="isColor('theme-yellow') ?
                                                'ring-2 ring-yellow-400 bg-yellow-400 text-black' :
                                                'bg-white text-black hover:bg-yellow-100'"
                                            class="px-4 py-2 rounded transition-colors border">
                                            Yellow Theme
                                        </button>

                                        <!-- Black Theme -->
                                        <button @click="setColorTheme('theme-black')"
                                            :class="isColor('theme-black') ?
                                                'ring-2 ring-gray-900 bg-black text-white' :
                                                'bg-white text-black hover:bg-gray-300'"
                                            class="px-4 py-2 rounded transition-colors border">
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
                                        <button @click="setDarkMode('dark')"
                                            :class="isMode('dark') ? 'ring-2 ring-blue-500 bg-gray-800 text-white' :
                                                'bg-gray-700 hover:bg-gray-800 text-gray-300'"
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
                                            <p class="text-center text-sm mt-1 dark:text-white">Dark</p>
                                        </button>

                                        <!-- Light Mode -->
                                        <button @click="setDarkMode('light')"
                                            :class="isMode('light') ? 'ring-2 ring-blue-500 bg-white text-gray-800' :
                                                'bg-gray-200 hover:bg-gray-100 text-gray-600 dark:bg-gray-700 dark:hover:bg-gray-800 dark:text-gray-300'"
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
                                            <p class="text-center text-sm mt-1 text-gray-800 dark:text-white">Light
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
</x-app-layout>
