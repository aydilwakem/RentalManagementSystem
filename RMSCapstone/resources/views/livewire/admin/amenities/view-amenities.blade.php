<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($amenities->isEmpty() && !$search && !$propertyFeatureType)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No amenities yet.<br> Click "Create Amenity" to add a new
                amenity.</p>
            <x-button class="mt-4" href="{{ route('admin.create-amenity') }}" icon="fas fa-plus" wire:navigate>
                Create Amenity
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
                @can('amenity-create')
                    <div class="mb-4">
                        <x-button icon="fas fa-plus" href="{{ route('admin.create-amenity') }}">
                            New Amenity
                        </x-button>
                    </div>
                @endcan
                <!-- Deleted Rooms (Restore and Delete Forever -->
                @can('amenity-soft-delete')
                    <x-button
                        class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2 mb-4"
                        icon="fas fa-trash" href="{{ route('admin.deleted-amenities') }}">
                        Deleted Amenities
                    </x-button>
                @endcan
            </div>
            <!-- Table -->
            <div
                class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                <!-- Header-->
                <div class="flex items-center justify-between d p-4">
                    <!-- Search-->
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
                                class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 focus:outline-none z-50
                                dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                                <div class="py-1">
                                    <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Bulk
                                        Delete</a>
                                </div>
                            </div>
                        </div>


                    </div>
                    {{-- Amenity Type Sort --}}
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class=" text-sm font-medium text-gray-900 dark:text-white">Amenity Type:</label>
                            <select wire:model.live="propertyFeatureType"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="">All</option>
                                <option value="appliance">Appliance</option>
                                <option value="equipment">Equipment</option>
                                <option value="utility">Utility</option>
                                <option value="entertainment">Entertainment</option>
                                <option value="service">Service</option>
                                <option value="fixture">Fixture</option>

                            </select>
                        </div>
                    </div>

                </div>
                {{-- End Header --}}

                {{-- Table Body --}}
                <div wire:loading wire:target="search, statusFilter"
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
                    <thead wire:loading.remove wire:target="search"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3 flex items-center space-x-2">
                                <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                    class="accent-blue-600 w-4 h-4">
                                <div class="flex items-center space-x-2 cursor-pointer" wire:click="setSortBy('id')">
                                    <span>Amenity ID</span>
                                    @if ($sortBy !== 'ID')
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
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-4 ml-1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>

                            {{-- Name --}}
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
                            <th scope="col" class="px-4 py-3">Quantity</th>
                            <th scope="col" class="px-4 py-3">Amenity Type</th>
                            <th scope="col" class="px-4 py-3">Status</th>

                            {{-- Actions --}}
                            <th scope="col" class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:loading.remove wire:target="search" class="text-left dark:bg-gray-700">
                        @forelse ($amenities as $amenity)
                            <tr
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap flex items-center space-x-2 dark:text-gray-200">
                                    <input wire:model.live="selectedRows" type="checkbox" name="amenities[]"
                                        value="{{ $amenity->id }}" class="accent-blue-600 w-4 h-4">
                                    <span>{{ $fakeIDs[$amenity->id] ?? 'AMY-???' }}</span>
                                </th>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $amenity->name }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $amenity->quantity }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-200">
                                    {{ ucfirst($amenity->property_feature_type) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($amenity->is_active)
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-500">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-500">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-3 dark:text-gray-200">

                                    @can('amenity-view')
                                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 hover:dark:text-blue-500"
                                            wire:navigate
                                            href="{{ route('admin.view-amenity', ['amenity' => $amenity->id]) }}">
                                        </i>
                                    @endcan


                                    @can('amenity-edit')
                                        <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 hover:dark:text-yellow-500"
                                            wire:navigate
                                            href="{{ route('admin.edit-amenity', ['amenity' => $amenity->id]) }}">
                                        </i>
                                    @endcan

                                    @can('amenity-delete')
                                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 hover:dark:text-red-500"
                                            wire:click="confirmDelete({{ $amenity->id }})" wire:loading.attr="disabled">
                                        </i>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <!-- No Match Search / Filter Result Message -->
                                <td colspan="15" class="text-center py-10 text-gray-500 dark:text-gray-200">
                                    No amenities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="py-6 px-4 bg-white shadow-sm dark:bg-gray-800 dark:text-white rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-3">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg p-2 w-24
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        {{ $amenities->links() }}
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Amenity') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete this item?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deleteAmenity" wire:loading.attr="disabled">
                            {{ __('Delete Amenity') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                <!-- Bulk Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Amenities') }}
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
                            {{ __('Delete Amenities') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                {{-- Cannot Delete Modal --}}
                <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                    <x-slot name="title">
                        {{ __('Unable to Delete') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('This amenity is currently active and cannot be deleted.') }}
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
