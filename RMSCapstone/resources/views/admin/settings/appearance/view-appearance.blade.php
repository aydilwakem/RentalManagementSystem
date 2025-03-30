<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Brand Appearance') }}
        </h2>
    </x-slot>


    <div class=" bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6">
            <div class="mx-auto max-w-2xl lg:py-2s">
                <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Modify Brand Appearance</h2>

                <form wire:submit.prevent="">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                        <!-- Name of Company -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                            <input type="text" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="Type company name" required>
                        </div>

                        <!-- Company Email -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Company Email</label>
                            <input type="email" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="Type company email" required>
                        </div>

                        <!-- Image Upload -->
                        <div class="sm:col-span-2">
                            <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Upload Image</label>
                            <input accept="image/png, image/jpeg" type="file" id="image"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        </div>

                        {{-- Color Picker --}}
                        <div class="space-y-2">
                            <label for="hs-color-input" class="block text-sm font-medium mb-2 dark:text-white">Color
                                picker</label>
                            <div class="grid grid-cols-5 gap-3">
                                <input type="color"
                                    class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700"
                                    id="hs-color-input" value="#2563eb" title="Choose your color">
                                <input type="color"
                                    class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700"
                                    id="hs-color-input" value="#166534" title="Choose your color">
                                <input type="color"
                                    class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700"
                                    id="hs-color-input" value="#880808" title="Choose your color">
                                <input type="color"
                                    class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700"
                                    id="hs-color-input" value="#F8C8DC" title="Choose your color">
                                <input type="color"
                                    class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700"
                                    id="hs-color-input" value="#FFBF00" title="Choose your color">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center space-y-2 mt-6">
                        <x-button href="{{ route('dashboard') }}" type="button"
                            class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                            Cancel
                        </x-button>
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                            wire:click="confirmCreate">
                            Save Appearance
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
