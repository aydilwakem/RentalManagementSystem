<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View User') }}
        </h2>
    </x-slot>

    <!-- Back Button -->
    <div class="flex justify-end mb-4">
        <button onclick="history.back()"
            class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
            <span class="leading-none translate-y-[-3px]">&times;</span>
        </button>
    </div>

    <h2 class="mb-2 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center">
        {{ $user->name }} {{ $user->last_name }}
    </h2>

    <!-- Profile Photo -->
    <div class="mb-6 flex justify-center">
        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/default-profile-photo.png') }}"
            alt="{{ $user->name }}" class="w-32 h-32 object-cover rounded-full shadow-md">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Left Column -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Email</h3>
            <p class=" text-gray-500 mb-3">{{ $user->email }}</p>

            <h3 class="text-lg font-semibold text-gray-900">Role</h3>
            <ul>
                @forelse($userRoles as $role)
                    <li>{{ $role }}</li>
                @empty
                    <li class="text-gray-500">No roles assigned.</li>
                @endforelse
            </ul>

            <div class="mt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Email Verified At</h3>
                <p class=" text-gray-500">{{ $user->email_verified_at ?? 'Not Verified' }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider">
                                Created At
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-semibold text-gray-800 uppercase tracking-wider">
                                Updated At
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->created_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->updated_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Permissions</h3>
            <div class="max-h-72 overflow-y-auto border rounded p-3 space-y-1 bg-gray-50">
                <ul class="space-y-1">
                    @forelse($userPermissions as $permission)
                        <li>{{ $permission }}</li>
                    @empty
                        <li class="text-gray-500">No permissions assigned.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between space-x-4 mt-6 mb-3">
        <!-- Delete -->
        <x-button type="button" icon="fas fa-trash"
            class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
            wire:click="confirmDelete({{ $user->id }})">
            Delete
        </x-button>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmItemDelete">
        <x-slot name="title">
            {{ __('Delete User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this user?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteUser({{ $user->id }})" wire:loading.attr="disabled">
                {{ __('Delete User') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
