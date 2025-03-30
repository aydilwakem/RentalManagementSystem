<div class="container mx-auto px-6 ">
    <div>
        <!-- Create Room Button -->
        @can('user-create')
        <div class="flex items-center justify-between px-1 mb-3">
            <x-button icon="fas fa-plus" href="{{ route('admin.create-user') }}">
                New User
            </x-button>
        </div>
        @endcan

        {{-- Display Session Message --}}
        @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
            {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
        @endif
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border mb-10">
            <!-- Header -->
            <div class="flex items-center justify-between p-4">
                <div class="flex">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor"
                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        {{-- Search Bar --}}
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                            placeholder="Search" required="">
                    </div>
                </div>

                {{-- Role Filter --}}
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="w-40 text-sm font-medium text-gray-900">Role Filter:</label>
                        <select wire:model.live="roleFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">All</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Columns --}}

        <table class="w-full text-left">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    {{-- ID --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                        <button class="flex items-center">
                            ID
                            @if ($sortBy !== 'id')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                            @else
                            @if ($sortDir == 'ASC')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                            @endif
                            @endif
                        </button>
                    </th>

                    {{-- Name --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                        <button class="flex items-center">
                            Name
                            @if ($sortBy !== 'name')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                            @else
                            @if ($sortDir == 'ASC')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                            @endif
                            @endif
                        </button>
                    </th>

                    <th scope="col" class="px-4 py-3">
                        Role
                    </th>

                    {{-- Email --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('email')">
                        <button class="flex items-center">
                            Email
                            @if ($sortBy !== 'email')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                            @else
                            @if ($sortDir == 'ASC')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                            @endif
                            @endif
                        </button>
                    </th>

                    {{-- Created at --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('created_at')">
                        <button class="flex items-center">
                            Created at
                            @if ($sortBy !== 'created_at')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                            @else
                            @if ($sortDir == 'ASC')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                            @endif
                            @endif
                        </button>
                    </th>


                    {{-- Updated at --}}
                    <th scope="col" class="px-4 py-3" wire:click="setSortBy('updated_at')">
                        <button class="flex items-center">
                            Updated at
                            @if ($sortBy !== 'updated_at')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                            @else
                            @if ($sortDir == 'ASC')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                            @endif
                            @endif
                        </button>
                    </th>

                    {{-- Actions --}}
                    <th scope="col" class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-left">
                @foreach ($users as $user)
                <tr class="border-b">
                    <th scope="row" class="font-medium text-gray-900 px-5">
                        {{ $user->id }}
                    </th>
                    <td class="p-2">{{ $user->name }}</td>

                    <td class="p-2">
                        <ul>
                            @forelse($user->getRoleNames() as $role)
                            <li>{{ $role }}</li>
                            @empty
                            <li class="text-gray-500">No roles assigned.</li>
                            @endforelse
                        </ul>
                    </td>

                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ $user->created_at }}</td>
                    <td class="p-2">{{ $user->updated_at }}</td>
                    <td class="px-4 py-3 flex items-center justify-center space-x-3">

                        <!-- View Icon -->
                        @can('user-view')
                        <i class="fas fa-eye text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.view-user', ['user' => $user->id]) }}">
                        </i>
                        @endcan

                        <!-- Edit Icon -->
                        @can('user-edit')
                        <i class="fas fa-edit text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.edit-user', ['user' => $user->id]) }}">
                        </i>
                        @endcan

                        <!-- Delete Icon -->
                        @can('user-delete')
                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                            wire:click="confirmDelete({{ $user->id }})">
                        </i>
                        @endcan

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Per Page --}}
        <div class="py-4 px-3">
            <div class="flex ">
                <div class="flex space-x-4 items-center">
                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                    <select wire:model.live='perPage'
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $users->links() }}
        </div>
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
</div>
