<div class="min-h-[550px] container mx-auto p-6 ">
    @if ($tenants->isEmpty() && !$search)
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No tenants yet.<br> Click "Create Tenant" to add a new tenant.
            </p>
            <x-button class="mt-4" href="{{ route('admin.create-tenant') }}" icon="fas fa-plus">
                Create Tenant
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

        <!-- Header Buttons -->
        <div>
            <div class="flex items-center justify-between">
                @can('tenant-create')
                    <div class="flex justify-between items-center mb-4">
                        <x-button icon="fas fa-plus" onclick="window.location.href='{{ route('admin.create-tenant') }}'">
                            New Tenant
                        </x-button>
                    </div>
                @endcan
                @can('tenant-soft-delete')
                    <x-button
                        class=" mb-4 !bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-tenants') }}">
                        Deleted Tenants
                    </x-button>
                @endcan
            </div>
        </div>

        <!-- Table Body -->
        <div
            class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <div class="flex items-center justify-between p-4">
                {{-- Search Tab --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    {{-- Search --}}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50
                        dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        <div class="py-1">
                            <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Bulk
                                Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <table class="w-full text-left">
                <thead
                    class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                    <tr>
                        <!-- Checkbox -->
                        <th scope="col" class="px-4 py-3 flex items-center space-x-2">
                            <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                class="accent-blue-600 w-4 h-4">
                            <!-- ID -->
                            <div class="flex items-center space-x-2 cursor-pointer" wire:click="setSortBy('id')">
                                <span>ID</span>
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
                            </div>
                        </th>

                        <!-- Tenant Name -->
                        <th scope="col" class="px-4 py-3" wire:click="setSortBy('first_name')">
                            <button class="flex items-center">
                                Tenant
                                @if ($sortBy !== 'first_name')
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

                        <!-- Assigned Property -->
                        <th scope="col" class="px-4 py-3">Assigned Property</th>

                        <!-- Email -->
                        <th scope="col" class="px-4 py-3">Email</th>

                        <!-- COntact Number -->
                        <th scope="col" class="px-4 py-3">Contact Number</th>

                        <!-- Actions -->
                        <th scope="col" class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="dark:bg-gray-700">
                    @forelse ($tenants as $tenant)
                        <tr
                            class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <input wire:model.live="selectedRows" type="checkbox" name="tenant[]"
                                    value="{{ $tenant->id }}" class="accent-blue-600 w-4 h-4">
                                <span> {{ $fakeIDs[$tenant->id] ?? 'TNT-???' }}</span>
                            </th>

                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                {{ $tenant->first_name }} {{ $tenant->last_name }}
                            </td>
                            <td class="px-4 py-3">
                                @php $hasProperty = false; @endphp
                                @foreach ($tenant->transactions as $transaction)
                                    @foreach ($transaction->properties as $property)
                                        {{ $property->name_number ?? 'N/A' }}<br>
                                        @php $hasProperty = true; @endphp
                                    @endforeach
                                @endforeach
                                @if (!$hasProperty)
                                    <span class="italic text-gray-600 dark:text-gray-200">No property assigned</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {!! $tenant->email ?? '<span class="text-gray-600 italic dark:text-gray-200">No email provided.</span>' !!} </td>
                            </td>
                            <td class="px-4 py-3">
                                {!! $tenant->contact_number ?? '<span class="text-gray-600 italic dark:text-gray-200">No contact number provided.</span>' !!}
                            </td>
                            <td class="px-4 py-3 flex items-center justify-center space-x-3">
                                @can('tenant-view')
                                    <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 hover:dark:text-blue-500" wire:navigate
                                        href="{{ route('admin.view-tenant', ['tenant' => $tenant->id]) }}">
                                    </i>
                                @endcan
                                @can('tenant-edit')
                                    <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 hover:dark:text-yellow-500" wire:navigate
                                        href="{{ route('admin.edit-tenant', ['tenant' => $tenant->id]) }}">
                                    </i>
                                @endcan
                                @can('tenant-delete')
                                    <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 hover:dark:text-red-500"
                                        wire:click="confirmDelete({{ $tenant->id }})">
                                    </i>
                                @endcan

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-10 text-gray-500">
                                No tenants found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-4 px-3 dark:bg-gray-800 dark:text-white">
                <div class="flex items-center justify-between">
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
                    {{ $tenants->links() }}
                </div>
            </div>

        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
        <x-slot name="title">{{ __('Delete Tenant') }}</x-slot>
        <x-slot name="content">{{ __('Are you sure you want to delete this tenant?') }}</x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmItemDelete', false)">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="deleteTenant">
                {{ __('Delete Tenant') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Bulk Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
        <x-slot name="title">
            {{ __('Delete Tenants') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete these items?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmBulkDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                {{ __('Delete Tenants') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>

    {{-- Cannot Delete Modal --}}
    <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
        <x-slot name="title">
            {{ __('Unable to Delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('This tenant has an active lease and cannot be deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                {{ __('OK') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
</div>
