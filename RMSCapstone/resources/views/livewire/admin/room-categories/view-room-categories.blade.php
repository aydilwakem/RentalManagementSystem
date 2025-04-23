<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Categories') }}
        </h2>
    </x-slot>
    @if ($roomCategories->isEmpty())
    <!-- Empty Page Message -->
    <div class="text-center py-10">
        <p class="text-gray-500 text-lg font-semibold">No rooms yet.<br> Click "Create Room" to add a new room.</p>
        <x-button class="mt-4" href="{{ route('admin.create-room-category') }}" icon="fas fa-plus">
            Create Room
        </x-button>
    </div>
    @else
    {{-- Display Session Message --}}
    @if (session('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                                                                                                            {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
        {{ session('message') }}
    </div>
    @endif
    <div>
        <div class="flex items-center justify-between">
            <!-- Create Room Button -->
            @can('room-category-create')
            <div class="flex items-center justify-between p-4">
                <x-button type="button" icon="fas fa-plus" href="{{ route('admin.create-room-category') }}">
                    New Category
                </x-button>
            </div>
            @endcan
            <!-- Deleted Rooms (Restore and Delete Forever -->
            @can('room-category-soft-delete')
            <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                icon="fas fa-trash" href="{{ route('admin.deleted-room-categories') }}">
                Deleted Room Categories
            </x-button>
            @endcan
        </div>
        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
            <!-- Header-->
            <div class="flex items-center justify-between d p-4">
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
                        {{-- Search --}}
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                            placeholder="Search" required="">
                    </div>
                </div>

                {{-- User type
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="w-40 text-sm font-medium text-gray-900">User Type :</label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="">All</option>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                </div>
                --}}

            </div>
            <!-- Table Body-->
            <table class="w-full text-left">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                            <button class="flex items-center">
                                ID
                                @if ($sortBy !== 'id')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                            <button class="flex items-center">
                                Name
                                @if ($sortBy !== 'name')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>

                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('description')">
                            <button class="flex items-center">
                                Description
                                @if ($sortBy !== 'description')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                                @else
                                @if ($sortDir == 'ASC')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-4 py-3">Amenities</th>
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                @foreach ($roomCategories as $roomCategory)
                <tr class="border-b">
                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                        {{ $fakeIDs[$roomCategory->id] ?? 'RCT-???' }}
                    </th>
                    <td class="px-4 py-3">{{ $roomCategory->name }}</td>
                    <td class="px-4 py-3">@if (!empty($roomCategory->description))
                        {{ $roomCategory->description }}
                        @else
                        <em class="text-gray-600 leading-relaxed">No description provided.</em>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($roomCategory->amenities->count() > 0)
                        {{ implode(', ', $roomCategory->amenities->pluck('name')->toArray()) }}
                        @else
                        No Amenities
                        @endif
                    </td>
                    <td class="px-4 py-3 flex items-center justify-center space-x-3">
                        <!-- View Icon -->
                        @can('room-category-view')
                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.view-room-category', ['roomCategory' => $roomCategory->id]) }}">
                        </i>
                        @endcan

                        <!-- Edit Icon -->
                        @can('room-category-edit')
                        <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                            href="{{ route('admin.edit-room-category', ['roomCategory' => $roomCategory->id]) }}">
                        </i>
                        @endcan

                        <!-- Delete Icon -->
                        @can('room-category-delete')
                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                            wire:click="confirmDelete({{ $roomCategory->id }})" wire:loading.attr="disabled">
                        </i>
                        @endcan

                    </td>
                </tr>
                @endforeach
            </table>

            {{-- Per Page --}}
            <div class="py-4 px-3">
                <div class="flex ">
                    <div class="flex space-x-2 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                        <select wire:model.live='perPage'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                {{ $roomCategories->links() }}
            </div>

            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete">
                <x-slot name="title">
                    {{ __('Delete Room Category') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteCategory" wire:loading.attr="disabled">
                        {{ __('Delete Room Category') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This category is currently in use and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>


        </div>
    </div>
    @endif

</div>