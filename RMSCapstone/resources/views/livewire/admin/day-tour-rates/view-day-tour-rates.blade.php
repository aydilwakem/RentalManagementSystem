<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($dayTourRates->isEmpty() && !$search && !$dayTourFilter && !$rateTypeFilter && !$dayTypeFilter)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No day tour rates yet.<br> Click "Create Day Tour Rate" to add a new rate.</p>
            <x-button class="mt-4" href="{{ route('admin.create-day-tour-rate') }}" icon="fas fa-plus">
                Create Day Tour Rate
            </x-button>
        </div>
    @else
        <!-- Display Session Message -->
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif

        <div>
            <div class="flex items-center justify-between">
                <!-- Create New -->
                @can('daytourrate-create')
                    <div class="flex justify-between items-center mb-4">
                        <x-button icon="fas fa-plus" onclick="window.location.href='{{ route('admin.create-day-tour-rate') }}'">
                            New Day Tour Rate
                        </x-button>
                    </div>
                @endcan
                <!-- Soft Deletes -->
                @can('daytourrate-soft-delete')
                    <x-button
                        class=" mb-4 !bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                        icon="fas fa-trash" href="{{ route('admin.deleted-day-tour-rates') }}">
                        Deleted Rates
                    </x-button>
                @endcan
            </div>
        </div>

        <!-- Table -->
        <div
            class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <!-- Header-->
            <div class="flex items-center justify-between p-4 dark:bg-gray-800 rounded-lg">
                <!-- Search Tab -->
                <div class="flex space-x-2">
                    <div class="relative w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor"
                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <!-- Search-->
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search rates..." required="">
                    </div>

                    <!-- Bulk Delete Button -->
                    @if($dayTourRates->count() > 0)
                    <div class="relative inline-block text-left" x-data="{ open: false }">
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
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Bulk
                                    Delete</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Filters -->
                <div class="flex space-x-3">
                    <div class="flex space-x-2 items-center">
                        <select wire:model.live="dayTourFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-40 p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All Tours</option>
                            @foreach ($dayTours as $tour)
                                <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="rateTypeFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-40 p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All Rate Types</option>
                            <option value="with_room">With Room</option>
                            <option value="without_room">Without Room</option>
                        </select>

                        <select wire:model.live="dayTypeFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-40 p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All Day Types</option>
                            <option value="weekday">Weekday</option>
                            <option value="weekend">Weekend</option>
                            <option value="holiday">Holiday</option>
                        </select>

                        <select wire:model.live="statusFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-32 p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Body-->
            <div wire:loading wire:target="search, dayTourFilter, rateTypeFilter, dayTypeFilter, statusFilter"
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
                <table class="min-w-full text-left">
                    <thead wire:loading.remove wire:target="search, dayTourFilter, rateTypeFilter, dayTypeFilter, statusFilter"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <!-- Select All Checkbox -->
                            <th scope="col" class="px-4 py-3 flex items-center space-x-2">
                                <input wire:model.live="selectPageRows" type="checkbox" id="checkAll"
                                    class="accent-blue-600 w-4 h-4">
                                <span>Select</span>
                            </th>

                            <!-- Rate Name -->
                            <th scope="col" class="px-4 py-3">Rate Name</th>

                            <!-- Day Tour -->
                            <th scope="col" class="px-4 py-3">Day Tour</th>

                            <!-- Rate Type -->
                            <th scope="col" class="px-4 py-3">Rate Type</th>

                            <!-- Day Type -->
                            <th scope="col" class="px-4 py-3">Day Type</th>

                            <!-- Adult Rate -->
                            <th scope="col" class="px-4 py-3">Adult Rate</th>

                            <!-- Kid Rate -->
                            <th scope="col" class="px-4 py-3">Kid Rate</th>

                            <!-- Status -->
                            <th scope="col" class="px-4 py-3">Status</th>

                            <!-- Actions -->
                            <th scope="col" class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:loading.remove wire:target="search, dayTourFilter, rateTypeFilter, dayTypeFilter, statusFilter" class="dark:bg-gray-700">
                        @forelse ($dayTourRates as $rate)
                            <tr
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap space-x-1 dark:text-white">
                                    <input wire:model.live="selectedRows" type="checkbox" name="rate[]"
                                        value="{{ $rate->id }}" class="accent-blue-600 w-4 h-4">
                                </th>
                                <td class="px-4 py-3">{{ $rate->rate_name }}</td>
                                <td class="px-4 py-3">{{ $rate->dayTour->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $rate->rate_type === 'with_room' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $rate->rate_type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $rate->day_type === 'holiday' ? 'bg-red-100 text-red-800' :
                                           ($rate->day_type === 'weekend' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $rate->day_type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($rate->adult_rate, 2) }}</td>
                                <td class="px-4 py-3 font-semibold text-green-600">₱{{ number_format($rate->kid_rate, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if ($rate->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-center space-x-2 mt-1">
                                    <!-- View Icon -->
                                    @can('daytourrate-view')
                                        <i class="fas fa-eye text-gray-700 hover:text-blue-600 cursor-pointer dark:text-gray-200 hover:dark:text-blue-500"
                                            wire:navigate href="{{ route('admin.view-day-tour-rate', ['dayTourRate' => $rate->id]) }}">
                                        </i>
                                    @endcan

                                    <!-- Edit Icon -->
                                    @can('daytourrate-edit')
                                        <i class="fas fa-edit text-gray-700 hover:text-yellow-600 cursor-pointer dark:text-gray-200 hover:dark:text-yellow-500"
                                            wire:navigate href="{{ route('admin.edit-day-tour-rate', ['dayTourRate' => $rate->id]) }}">
                                        </i>
                                    @endcan

                                    <!-- Delete Icon -->
                                    @can('daytourrate-delete')
                                        <i class="fas fa-trash-alt text-gray-700 hover:text-red-600 cursor-pointer dark:text-gray-200 hover:dark:text-red-500"
                                            wire:click="confirmDelete({{ $rate->id }})" wire:loading.attr="disabled">
                                        </i>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <!-- No Match Search / Filter Result Message -->
                                <td colspan="9" class="text-center py-10 text-gray-500 dark:text-white">
                                    No day tour rates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="py-4 px-3">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                        <select wire:model.live='perPage'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
                {{ $dayTourRates->links() }}
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
            <x-slot name="title">
                {{ __('Delete Day Tour Rate') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this day tour rate?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteDayTourRate" wire:loading.attr="disabled">
                    {{ __('Delete Rate') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Bulk Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmBulkDelete" type="danger">
            <x-slot name="title">
                {{ __('Delete Day Tour Rates') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete these day tour rates?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmBulkDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                    {{ __('Delete Rates') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('These day tour rates are currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
