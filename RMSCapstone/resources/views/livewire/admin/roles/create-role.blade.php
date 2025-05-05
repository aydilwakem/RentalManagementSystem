<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">

    <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Add new role</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="py-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="saveRole">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

            <!-- Name of Role -->
            <div class="sm:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input type="text" wire:model="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter Role" required>
            </div>

            <!-- Permissions List -->
            <div class="sm:col-span-2">
                <label for="permissions" class="block mb-2 text-sm font-medium text-gray-900">Permissions</label>
                <div class="space-y-4">

                    <!-- Permissions Accordion (Dropdown with Checkboxes) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($groupedPermissions as $group => $permissions)
                            @if ($permissions->count())
                                <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                                    <button type="button" @click="open = !open"
                                        class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                                        {{ $group }}
                                        <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open" class="p-4 space-y-2">
                                        @foreach ($permissions as $permission)
                                            <label class="flex items-center space-x-2 text-sm">
                                                <input type="checkbox" wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}" class="rounded border-gray-300">
                                                <span>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

        <div class="flex justify-between items-center space-y-2 mt-8">
            <x-button onclick="history.back()" type="button"
                class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                Cancel
            </x-button>
            <x-button type="submit" class="mt-6" wire:loading.attr="disabled" wire:target="image">
                Add Role
            </x-button>
        </div>

    </form>
</div>
