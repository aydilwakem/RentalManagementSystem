<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    @if ($inclusions->isEmpty() && !$search)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No inclusions yet.<br> Click "Create Inclusion" to add a new
                feature.</p>
            <x-button class="mt-4" href="{{ route('admin.create-inclusion') }}" icon="fas fa-plus" wire:navigate>
                Create Inclusion
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
            <div class="flex items-center justify-between mb-3">

                @can('event-inclusions-create')
                    <x-button icon="fas fa-plus" href="{{ route('admin.create-inclusion') }}">
                        New Inclusion
                    </x-button>
                @endcan

                <!-- Deleted Rooms (Restore and Delete Forever -->
                @can('event-inclusions-soft-delete')
                    <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-inclusions') }}">
                        Deleted Inclusions
                    </x-button>
                @endcan
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
                <!-- Header-->

                <!-- Table -->
                <div class="bg-white rounded-lg shadow-md overflow-x-auto border">
                    <!-- Header-->
                    <div class="flex items-center justify-between p-4">
                        {{-- Search Tab --}}
                        <div class="flex">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                    placeholder="Search" required="">
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
                    <div wire:loading wire:target="search"
                        class="w-full flex items-center justify-center min-h-[50px] relative mt-24">
                        <div class="flex flex-col items-center justify-center text-center">
                            <!-- Spinner -->
                            <svg class="animate-spin h-6 w-6 text-green-700 mb-2" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                            </svg>
                            <span class="text-green-700 text-sm">Loading...</span>
                        </div>
                    </div>
                    <table class="w-full text-left">
                        <thead wire:loading.remove wire:target="search" class="text-sm text-gray-700 bg-gray-200">
                            <tr>
                                <th scope="col" class="px-4 py-3 flex items-center space-x-2">
                                    <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                        class="accent-blue-600 w-4 h-4">
                                    {{-- ID --}}
                                    <div class="flex items-center space-x-2 cursor-pointer"
                                        wire:click="setSortBy('id')">
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
                                    </div>
                                </th>

                                {{-- Name --}}
                                <th scope="col" class="px-4 py-3" wire:click="setSortBy('name')">
                                    <button class="flex items-center">
                                        Name
                                        @if ($sortBy !== 'name')
                                            {{-- Default icon when sorting is not active --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
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

                                {{-- Actions --}}
                                <th scope="col" class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody wire:loading.remove wire:target="search" class="text-left">
                            @forelse ($inclusions as $inclusion)
                                <tr class="border-b hover:bg-gray-50">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        <input wire:model.live="selectedRows" type="checkbox" name="inclusions[]"
                                            value="{{ $inclusion->id }}" class="accent-blue-600 w-4 h-4 me-2">
                                        {{ $fakeIDs[$inclusion->id] ?? 'FTR-???' }}
                                    </th>
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $inclusion->name }}</td>
                                    <td class="px-4 py-3 flex items-center justify-center space-x-4">

                                        @can('event-inclusions-view')
                                            {{-- View --}}
                                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer"
                                                wire:navigate
                                                href="{{ route('admin.view-inclusion', ['inclusion' => $inclusion->id]) }}">
                                            </i>
                                        @endcan

                                        @can('event-inclusions-edit')
                                            {{-- Edit --}}
                                            <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer"
                                                wire:navigate
                                                href="{{ route('admin.edit-inclusion', ['inclusion' => $inclusion->id]) }}">
                                            </i>
                                        @endcan

                                        @can('event-inclusions-delete')
                                            {{-- Delete --}}
                                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer"
                                                wire:click="confirmDelete({{ $inclusion->id }})"
                                                wire:loading.attr="disabled">
                                            </i>
                                        @endcan


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center py-10 text-gray-500">
                                        No inclusions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>


                    {{-- Pagination --}}
                    <div class="py-6 px-4 !bg-white rounded-xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-3">
                                <label class="text-sm font-semibold text-gray-700">Per Page</label>
                                <select wire:model.live="perPage"
                                    class="!bg-white border border-gray-300 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 w-24">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            {{ $inclusions->links() }}
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                        <x-slot name="title">
                            {{ __('Delete Inclusion') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Are you sure you want to delete this item?') }}
                        </x-slot>

                        <x-slot name="footer">
                            <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                                wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3" wire:click="deleteInclusion"
                                wire:loading.attr="disabled">
                                {{ __('Delete Inclusion') }}
                            </x-danger-button>
                        </x-slot>
                    </x-dialog-modal>

                    <!-- Bulk Delete Confirmation Modal -->
                    <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
                        <x-slot name="title">
                            {{ __('Delete Inclusions') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Are you sure you want to delete these items?') }}
                        </x-slot>

                        <x-slot name="footer">
                            <x-secondary-button wire:click="$set('confirmBulkDelete', false)"
                                wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-danger-button class="ms-3" wire:click="deleteSelectedRows"
                                wire:loading.attr="disabled">
                                {{ __('Delete Inclusions') }}
                            </x-danger-button>
                        </x-slot>
                    </x-dialog-modal>
                </div>
            </div>
    @endif
</div>
