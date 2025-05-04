<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Brand Appearance') }}
        </h2>
    </x-slot>

    <!-- Wrap in Alpine for state management -->
    <div
        x-data="{ darkMode: false, fontSize: 'text-base' }"
        :class="fontSize"
        class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8"
    >
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Modify Brand Appearance</h2>

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Company -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Name</label>
                    <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type company name" required>
                </div>

                <!-- Company Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Email</label>
                    <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Type company email" required>
                </div>

                <!-- Image Upload -->
                <div class="sm:col-span-2">
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Image</label>
                    <input accept="image/png, image/jpeg" type="file" id="image" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                </div>

                <!-- Color Picker -->
                <div class="space-y-2">
                    <label for="hs-color-input" class="block text-sm font-medium mb-2 dark:text-white">Accent Color</label>
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="color in ['#2563eb','#166534','#880808','#F8C8DC','#FFBF00']">
                            <input :value="color" type="color" class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg dark:bg-neutral-900 dark:border-neutral-700" title="Choose your color">
                        </template>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Theme Mode</label>
                    <div class="flex gap-4">
                        <button @click.prevent="darkMode = false" class="w-20 h-20 border rounded-lg overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full">
                                <rect width="100" height="100" fill="#ffffff" />
                                <circle cx="50" cy="30" r="10" fill="#facc15" />
                                <rect x="20" y="50" width="60" height="30" rx="5" fill="#e5e7eb" />
                            </svg>
                        </button>

                        <button @click.prevent="darkMode = true" class="w-20 h-20 border rounded-lg overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-full h-full">
                                <rect width="100" height="100" fill="#1f2937" />
                                <path d="M60,30a15,15 0 1,0 -20,20a12,12 0 1,1 20,-20" fill="#facc15" />
                                <rect x="20" y="50" width="60" height="30" rx="5" fill="#374151" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Font Size Selection -->
                <div class="sm:col-span-2 space-y-2">
                    <label class="block text-sm font-medium mb-2 text-gray-900 dark:text-white">Font Size</label>
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
                <x-button type="submit" wire:loading.attr="disabled" wire:target="image" wire:click="confirmCreate">
                    Save Appearance
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>
