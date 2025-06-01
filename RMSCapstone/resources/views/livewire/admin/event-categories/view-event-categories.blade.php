<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($eventCategories->isEmpty() && !$search)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No event categories yet.<br> Click "Create Event Category" to
                add a new event category.
            </p>
            <x-button class="mt-4" href="{{ route('admin.create-event-category') }}" icon="fas fa-plus">
                Create Event Category
            </x-button>
        </div>
    @else
        <div>
            <div class="flex items-center justify-between mb-4">
                <!-- Create Room Button -->
                @can('event-category-create')
                    <div class="flex items-center justify-between">
                        <x-button icon="fas fa-plus" href="{{ route('admin.create-event-category') }}">
                            New Event Category
                        </x-button>
                    </div>
                @endcan
                @can('event-category-soft-delete')
                    <!-- Deleted Items (Restore and Delete Forever) -->
                    <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-event-categories') }}">
                        Deleted Categories
                    </x-button>
                @endcan
            </div>

            {{-- Display Session Message --}}
            @if (session('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                    class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
                <!-- Header-->
                <div class="flex items-center p-4">
                    {{-- Search Bar --}}
                    <div class="flex w-full">
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
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                    placeholder="Search" required="">
                            </div>
                        </div>

                        {{-- Bulk Actions Button --}}
                        <div class="relative inline-block text-left ml-2" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Bulk
                                        Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200">
                        <tr>
                            {{-- Checkboxes --}}
                            <th scope="col" class="px-4 py-3 flex items-center space-x-2">
                                <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                    class="accent-blue-600 w-4 h-4">
                                <div class="flex items-center space-x-2 cursor-pointer" wire:click="setSortBy('id')">
                                    <span>ID</span>

                                    @if ($sortBy !== 'id')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            {{-- Up arrow (Ascending) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            {{-- Down arrow (Descending) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                    </button>

                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                                <button class="flex items-center">
                                    Name
                                    @if ($sortBy !== 'name')
                                        {{-- Default icon when sorting is not active --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    @else
                                        @if ($sortDir == 'ASC')
                                            {{-- Up arrow (Ascending) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        @else
                                            {{-- Down arrow (Descending) --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                            {{-- <th scope="col" class="px-4 py-3">
                            <span class="sr-only">Actions</span>
                        </th> --}}
                        </tr>
                    </thead>
                    @forelse ($eventCategories as $eventCategory)
                        <tr class="border-b hover:bg-gray-50">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                <input wire:model.live="selectedRows" type="checkbox" name="eventCategory[]"
                                    value="{{ $eventCategory->id }}" class="accent-blue-600 w-4 h-4 me-2">
                                {{ $fakeIDs[$eventCategory->id] ?? 'ECT-???' }}
                            </th>
                            <td class="px-4 py-3 text-gray-900 font-semibold"> {{ $eventCategory->name }}</td>
                            <td class="px-4 py-3">
                                @if (!empty($eventCategory->description))
                                    {{ Str::limit($eventCategory->description, 100) }}
                                @else
                                    <em class="text-gray-600 leading-relaxed">No description provided.</em>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-3">
                                <!-- View Icon -->
                                @can('event-category-view')
                                    <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer" wire:navigate
                                        href="{{ route('admin.view-event-category', ['eventCategory' => $eventCategory->id]) }}">
                                    </i>
                                @endcan

                                <!-- Edit Icon -->
                                @can('event-category-edit')
                                    <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer" wire:navigate
                                        href="{{ route('admin.edit-event-category', ['eventCategory' => $eventCategory->id]) }}">
                                    </i>
                                @endcan

                                <!-- Delete Icon -->
                                @can('event-category-delete')
                                    <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                        wire:click="confirmDelete({{ $eventCategory->id }})"
                                        wire:loading.attr="disabled">
                                    </i>
                                @endcan

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-10 text-gray-500">
                                No event categories found.
                            </td>
                        </tr>
                    @endforelse
                </table>

                {{-- Pagination --}}
                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $eventCategories->links() }}
                </div>

                <!-- Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemDelete">
                    <x-slot name="title">
                        {{ __('Delete Event Category') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete this item?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteEventCategory"
                            wire:loading.attr="disabled">
                            {{ __('Delete Event Category') }}
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

                <!-- Bulk Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmBulkDelete">
                    <x-slot name="title">
                        {{ __('Delete Event Categories') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete these items?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmBulkDelete', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                            {{ __('Delete Event Categories') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>
            </div>
    @endif
</div>
