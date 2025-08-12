<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($allOldMaintenances->count() === 0)
        <!-- Navigation Tabs -->
        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 border-gray-300">
            <li class="me-2">
                <a href="{{ route('admin.maintenances') }}"
                    class="inline-block p-4 {{ Route::is('admin.maintenances') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg dark:text-gray-200' }}">
                    Pending Maintenances
                </a>
            </li>
            <li class="me-2">
                <a href="{{ route('admin.old-maintenances') }}"
                    class="inline-block p-4 {{ Route::is('admin.old-maintenances') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg dark:text-gray-200' }}">
                    Old Maintenances
                </a>
            </li>
        </ul>

        <div class="bg-white rounded-lg overflow-x-auto">
            <!-- Empty Table Message -->
            <div class="text-center py-10">
                <p class="text-gray-500 text-lg font-semibold">No maintenances yet.<br> Click "View Maintenances"
                    to
                    view maintenance.</p>
                <x-button class="mt-4" href="{{ route('admin.maintenances') }}" icon="fas fa-eye" wire:navigate>
                    View New Maintenances
                </x-button>
            </div>
        </div>
    @else
        <div>

            {{-- Display Session Message --}}
            @if (session('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                    class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="flex items-center justify-between">
                <ul class="flex items-center text-sm font-medium text-center text-gray-600 border-gray-300">
                    <li class="me-2">
                        <a href="{{ route('admin.maintenances') }}"
                            class="inline-block p-4 {{ Route::is('admin.maintenances') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg dark:text-gray-200 border dark:border-gray-700' }}">
                            Pending Maintenances
                        </a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('admin.old-maintenances') }}"
                            class="inline-block p-4 {{ Route::is('admin.old-maintenances') ? 'text-green-700 bg-green-100 font-semibold rounded-t-lg' : 'hover:text-green-700 hover:bg-green-50 rounded-t-lg dark:text-gray-200 border dark:border-gray-700' }}">
                            Old Maintenances
                        </a>
                    </li>
                </ul>
                <!-- Header-->
                <div class="flex items-center justify-between transform -translate-y-2">
                    <x-button
                        class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-maintenances') }}">
                        Deleted Maintenances
                    </x-button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
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
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Search" required="">
                        </div>

                        {{-- Bulk Actions Button --}}
                        <div class="relative inline-block text-left ml-2" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50
                                dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                                Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50
                                dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                                <div class="py-1">
                                    <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Bulk
                                        Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Maintenance Type Sort --}}
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="text-sm font-medium text-gray-900 dark:text-gray-200">Status:</label>
                            <select wire:model.live="priorityStatus"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">All</option>
                                <option value="emergency">Emergency</option>
                                <option value="urgent">Urgent</option>
                                <option value="routine">Routine</option>
                                <option value="planned">Planned</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 flex items-center space-x-2 mt-3">
                                <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                    class="accent-blue-600 w-4 h-4">
                                <div class="flex items-center space-x-2 cursor-pointer" wire:click="setSortBy('id')">
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
                            <th scope="col" class="px-4 py-3">Assigned Property</th>
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('reported_at')">
                                <button class="flex items-center">
                                    Date Reported
                                    @if ($sortBy !== 'reported_at')
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
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('resolved_at')">
                                <button class="flex items-center">
                                    Date Resolved
                                    @if ($sortBy !== 'resolved_at')
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
                            <th scope="col" class="px-4 py-3" wire:click="setSortBy('priority_status')">
                                <button class="flex items-center">
                                    Priority Status
                                    @if ($sortBy !== 'priority_status')
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
                            <th scope="col" class="px-4 py-3 text-center">Action</th>
                            {{-- <th scope="col" class="px-4 py-3">
                            <span class="sr-only">Actions</span>
                        </th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-left dark:bg-gray-700">
                        @forelse ($maintenances as $maintenance)
                            <tr class="border-b dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1 dark:text-white">
                                    <input wire:model.live="selectedRows" type="checkbox" name="maintenances[]"
                                        value="{{ $maintenance->id }}" class="accent-blue-600 w-4 h-4">
                                    <span>{{ $fakeIDs[$maintenance->id] ?? 'MNT-???' }}</span>
                                </th>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200">
                                    {{ $maintenance->name }}
                                </th>
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-gray-200">
                                    {{ $maintenance->property->name_number ?? 'No Assigned Property' }}
                                </th>
                                <td class="px-4 py-3">
                                    {{ $maintenance->reported_at->format('F j, Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($maintenance->resolved_at)
                                        {{ $maintenance->resolved_at->format('F j, Y') }}
                                    @else
                                        Unresolved Maintenance
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($maintenance->priority_status === 'planned')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-600">
                                            Planned
                                        </span>
                                    @elseif($maintenance->priority_status === 'routine')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                            Routine
                                        </span>
                                    @elseif($maintenance->priority_status === 'urgent')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                            Urgent
                                        </span>
                                    @elseif($maintenance->priority_status === 'emergency')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                            Emergency
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-3">

                                    <!-- View Icon -->
                                    @can('maintenance-view')
                                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 dark:hover:text-blue-500"
                                            wire:navigate
                                            href="{{ route('admin.view-maintenance', ['maintenance' => $maintenance->id]) }}">
                                        </i>
                                    @endcan

                                    <!-- Delete Icon -->
                                    @can('maintenance-delete')
                                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 dark:hover:text-red-500"
                                            wire:click="confirmDelete({{ $maintenance->id }})"
                                            wire:loading.attr="disabled">
                                        </i>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center py-10 text-gray-500">
                                    No maintenances found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>


                {{-- Pagination --}}
                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $maintenances->links() }}
                </div>

                <!-- Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Maintenance') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete this item?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteMaintenances" wire:loading.attr="disabled">
                            {{ __('Delete Maintenance') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                <!-- Bulk Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Maintenances') }}
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
                            {{ __('Delete Maintenances') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

            </div>
        </div>
    @endif
</div>
