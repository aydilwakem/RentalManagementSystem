<div class="min-h-[550px] container mx-auto p-8 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View User') }}
        </h2>
    </x-slot>

    <div class="py-3 px-8 mx-auto max-w-2xl border rounded-lg bg-white shadow-md">
        <h2 class="mb-6 text-xl font-semibold leading-none text-gray-900 md:text-2xl text-center">
            {{ $user->name }}
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

                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-900">Email Verified At</h3>
                    <p class=" text-gray-500">{{ $user->email_verified_at ?? 'Not Verified' }}</p>
                </div>

                <div class="flex justify-between mb-3">
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">Created At</h3>
                        <p class="text-sm text-gray-500">{{ $user->created_at }}</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">Updated At</h3>
                        <p class="text-sm text-gray-500">{{ $user->updated_at }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Permissions</h3>
                <div class="max-h-60 overflow-y-auto border rounded p-3 space-y-1 bg-gray-50">
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

            <!-- Edit -->
            <x-button type="button" icon="fas fa-pen-to-square"
                class="!text-black inline-flex items-center !bg-gray-200 hover:!bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:navigate href="{{ route('admin.edit-user', ['user' => $user->id]) }}">
                Edit
            </x-button>

            <!-- Delete -->
            <x-button type="button" icon="fas fa-trash"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                wire:click="deleteUser({{ $user->id }})">
                Delete
            </x-button>

        </div>
    </div>
</div>
