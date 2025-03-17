<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($eventCategories->isEmpty())
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
        <!-- Create Room Button -->
        @can('event-category-create')
        <div class="flex items-center justify-between p-4">
            <x-button icon="fas fa-plus" href="{{ route('admin.create-event-category') }}">
                New Event Category
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

                    {{-- User Type Sort
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
                    </div> --}}
                </div>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('id')">
                            <button class="flex items-center">
                                ID
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
                        </th>
                        <th scope="col" class="px-4 py-3">Description</th>
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        {{-- <th scope="col" class="px-4 py-3">
                            <span class="sr-only">Actions</span>
                        </th> --}}
                    </tr>
                </thead>
                @foreach ($eventCategories as $eventCategory)
                <tr class="border-b">
                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                        {{ $fakeIDs[$eventCategory->id] ?? 'ECT-???' }}
                    </th>
                    <td class="px-4 py-3"> {{ $eventCategory->name }}</td>
                    <td class="px-4 py-3"> {{ $eventCategory->description }}</td>
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
                            wire:click="deleteEventCategory({{ $eventCategory->id }})">
                        </i>
                        @endcan

                    </td>
                </tr>
                @endforeach
            </table>

            {{-- Pagination --}}
            <div class="py-4 px-3">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                        <select wire:model.live="perPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                {{ $eventCategories->links() }}
            </div>
        </div>
        @endif
    </div>