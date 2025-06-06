<x-app-layout>
    <div>
        <!-- Header -->
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-surface leading-tight">
                {{ __('Brand Appearance') }}
            </h2>
        </x-slot>

        <!-- Body Container -->
        <div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6">

                <div class="relative flex items-center">
                    <!-- Title -->
                    <h2 class="text-2xl font-bold text-gray-900 w-full text-center">Customize Brand Appearance</h2>

                    <!-- Back Button -->
                    <button onclick="history.back()"
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                        <span class="leading-none translate-y-[-3px]">&times;</span>
                    </button>
                </div>

                <!-- Form container -->

                <!-- Wrap in Alpine for state management -->
                <div x-data="{ darkMode: false, fontSize: 'text-base' }" :class="fontSize"
                    class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl">

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
                                <label class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Theme
                                    Mode</label>
                                <div class="flex gap-4">
                                    <button @click.prevent="darkMode = false"
                                        class="w-20 h-20 border rounded-lg overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                            class="w-full h-full">
                                            <rect width="100" height="100" fill="#ffffff" />
                                            <circle cx="50" cy="30" r="10" fill="#facc15" />
                                            <rect x="20" y="50" width="60" height="30" rx="5"
                                                fill="#e5e7eb" />
                                        </svg>
                                    </button>

                                    <button @click.prevent="darkMode = true"
                                        class="w-20 h-20 border rounded-lg overflow-hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                            class="w-full h-full">
                                            <rect width="100" height="100" fill="#1f2937" />
                                            <path d="M60,30a15,15 0 1,0 -20,20a12,12 0 1,1 20,-20" fill="#facc15" />
                                            <rect x="20" y="50" width="60" height="30" rx="5"
                                                fill="#374151" />
                                        </svg>
                                    </button>
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
