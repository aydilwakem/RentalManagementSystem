<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role details') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border bg-white">

        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Role</h2>

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

        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Name of Role -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Role Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter Role" required>
                </div>

                <!-- Permissions List -->
                <div class="sm:col-span-2">
                    <label for="permissions" class="block mb-2 text-sm font-medium text-gray-900">Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($permissions as $permission)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                                class="rounded border-gray-300 text-blue-600">
                            <span>{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- <div x-data="{ open: false }" class="border rounded-lg bg-gray-50">
                    <button type="button" @click="open = !open"
                        class="w-full text-left px-4 py-2 font-semibold flex justify-between items-center">
                        Rooms
                        <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="p-4 space-y-2">
                        @foreach ($permissions->filter(fn($p) => preg_match('/^room-(?!rate|category)/', $p->name)) as
                        $permission)
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                class="rounded border-gray-300">
                            <span>{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div> --}}
            </div>

            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:loading.attr="disabled" wire:target="image"
                    wire:click="confirmEdit({{ $role->id }})">
                    Update Role
                </x-button>
            </div>
        </form>
    </div>
    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Update Role') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this role?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateRole({{ $role->id }})"
                wire:loading.attr="disabled">
                {{ __('Update Role') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>