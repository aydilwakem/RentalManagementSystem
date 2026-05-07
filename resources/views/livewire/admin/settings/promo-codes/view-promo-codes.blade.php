<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($promoCodes->isEmpty() && !$search)
        <!-- Empty Table Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No promo codes yet.<br> Click "Create Promo Code" to add a new
                promo code.</p>
            <x-button class="mt-4" href="{{ route('admin.create-promo-code') }}" icon="fas fa-plus" wire:navigate>
                Create Promo Code
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
            <div class="flex items-center justify-between mb-4 gap-1">
                <!-- Create Promo Code Button -->
                @can('promo-code-create')
                    <div class="flex items-center justify-between">
                        <x-button href="{{ route('admin.create-promo-code') }}" icon="fas fa-plus">
                            New Promo Code
                        </x-button>
                    </div>
                @endcan

                <!-- Soft Delete Button -->
                @can('promo-code-soft-delete')
                    <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-promo-codes') }}">
                        Deleted Promo Codes
                    </x-button>
                @endcan
            </div>

            <div
                class="bg-white rounded-lg shadow-md  border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                <!-- Header-->
                <div class="flex items-center justify-between p-4 dark:bg-gray-800 rounded-lg">
                    <!-- Search Tab -->
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
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Search" required="">
                        </div>
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
                            dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                            <div class="py-1">
                                <a wire:click.prevent="confirmDeleteInBulk" href="#"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Bulk
                                    Delete</a>
                            </div>
                        </div>
                    </div>
                    {{-- <span class="ml-2">Selected 10 activities</span> --}}
                </div>
                <!-- Table -->
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
                <div class="overflow-x-auto">
                    <table class="w-full text-left ">
                        <thead wire:loading.remove wire:target="search"
                            class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700 ">
                            <tr>
                                {{-- ID --}}
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
                                    </div>
                                </th>

                                {{-- Code --}}
                                <th scope="col" class="px-4 py-3 ">Discount Code</th>

                                {{-- Details --}}
                                {{-- <th scope="col" class="px-4 py-3 ">Name/Description</th> --}}
                                <th scope="col" class="px-4 py-3 ">Discount Value</th>
                                <th scope="col" class="px-4 py-3 ">Code Uses</th>
                                <th scope="col" class="px-4 py-3 ">Validity</th>
                                <th scope="col" class="px-4 py-3 ">Applied To</th>
                                <th scope="col" class="px-4 py-3 ">Status</th>
                                <th scope="col" class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody wire:loading.remove wire:target="search" class="dark:bg-gray-700">
                            @forelse ($promoCodes as $promoCode)
                                <tr
                                    class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1 dark:text-white">
                                        <input wire:model.live="selectedRows" type="checkbox" name="promoCodes[]"
                                            value="{{ $promoCode->id }}" class="accent-blue-600 w-4 h-4">
                                        <span>{{ $fakeIDs[$promoCode->id] ?? 'CODE-???' }}</span>
                                    </th>
                                    <td class="px-4 py-3 text-gray-900 font-semibold dark:text-gray-200">
                                        {{ $promoCode->code }}</td>
                                    {{-- <td class="px-4 py-3">
                                @if (!empty($promoCode->description))
                                {{ Str::limit($promoCode->description, 50) }}
                                @else
                                <em class="text-gray-600 leading-relaxed dark:text-gray-200">No description
                                    provided.</em>
                                @endif
                            </td> --}}
                                    <td class="px-4 py-3">
                                        @if ($promoCode->discount_type == 'fixed')
                                            ₱{{ number_format($promoCode->discount_value, 2) }}
                                        @else
                                            {{ number_format($promoCode->discount_value) }}%
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $promoCode->uses_count }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($promoCode->has_expiration == 0)
                                            <p>No Expiry</p>
                                        @else
                                            <p>
                                                {{ \Carbon\Carbon::parse($promoCode->start_date)->format('F j, Y') }} <br>
                                                <span class="pl-8">to</span> <br>
                                                {{ \Carbon\Carbon::parse($promoCode->end_date)->format('F j, Y') }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $promoCode->propertyCategory->name ?? 'All Rooms' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($promoCode->is_active)
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
                                    <td class="px-4 py-3 flex items-center justify-center space-x-2">

                                        <!-- View Icon -->
                                        @can('promo-code-view')
                                            <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 dark:hover:text-blue-500"
                                                wire:navigate
                                                href="{{ route('admin.view-promo-code', ['promoCode' => $promoCode->id]) }}">
                                            </i>
                                        @endcan

                                        <!-- Edit Icon -->
                                        @can('promo-code-edit')
                                            <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 dark:hover:text-yellow-500"
                                                wire:navigate
                                                href="{{ route('admin.edit-promo-code', ['promoCode' => $promoCode->id]) }}">
                                            </i>
                                        @endcan

                                        <!-- Delete Icon -->
                                        @can('promo-code-soft-delete')
                                            <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 dark:hover:text-red-500"
                                                wire:click="confirmDelete({{ $promoCode->id }})"
                                                wire:loading.attr="disabled">
                                            </i>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center py-10 text-gray-500 dark:text-white">
                                        No promo codes found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                <!-- Pagination -->
                <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
                    <div class="flex">
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
                    {{ $promoCodes->links() }}
                </div>


                <!-- Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Promo Code') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('Are you sure you want to delete this item?') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('confirmItemDelete', false)"
                            wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button class="ms-3" wire:click="deletePromoCode" wire:loading.attr="disabled">
                            {{ __('Delete Promo Code') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                <!-- Bulk Delete Confirmation Modal -->
                <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
                    <x-slot name="title">
                        {{ __('Delete Promo Codes') }}
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
                            {{ __('Delete Promo Codes') }}
                        </x-danger-button>
                    </x-slot>
                </x-dialog-modal>

                {{-- Cannot Delete Modal --}}
                <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                    <x-slot name="title">
                        {{ __('Unable to Delete') }}
                    </x-slot>

                    <x-slot name="content">
                        {{ __('This promo code is currently active and cannot be deleted.') }}
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                            {{ __('OK') }}
                        </x-secondary-button>
                    </x-slot>
                </x-dialog-modal>


            </div>
    @endif
</div>
