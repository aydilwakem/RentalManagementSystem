<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    <div class="mb-4">

        <!-- Back Button -->
        <div class="mb-4">
            <x-ghost-button href="{{ route('admin.reservations-list') }}"  icon="fas fa-chevron-left" wire:navigate
                class="inline-flex items-center text-gray-700 hover:text-gray-900 font-semibold focus:outline-none hover:underline">
                Back to Reservations
            </x-ghost-button>
        </div>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Showing reservations older than 5 years that have been automatically archived.
        </p>
    </div>

    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg bg-green-500 text-white">
            {{ session('message') }}
        </div>
    @endif

    <!-- Table Container -->
    <div
        class="bg-white rounded-lg shadow-md border relative z-0 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 dark:bg-gray-800 rounded-lg">
            <!-- Search -->
            <div class="flex">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Search archived reservations..." required>
                </div>
            </div>
        </div>

        <!-- Table Body -->
        <div wire:loading wire:target="search"
            class="w-full flex items-center justify-center min-h-[50px] relative mt-24">
            <div class="flex flex-col items-center justify-center text-center">
                <svg class="animate-spin h-6 w-6 text-green-700 mb-2" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                </svg>
                <span class="text-green-700 text-sm">Loading...</span>
            </div>
        </div>

        <div>
            <div class="overflow-y-auto overflow-x-auto max-h-[450px] max-w-screen">
                <table class="min-w-full text-left">
                    <thead wire:loading.remove wire:target="search"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700 sticky top-0 z-10">
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

                            <th scope="col" class="px-4 py-3">Guest Name</th>
                            <th scope="col" class="px-4 py-3">Room/s</th>
                            <th scope="col" class="px-4 py-3">Pax</th>
                            <th scope="col" class="px-4 py-3">Stay Duration</th>
                            <th scope="col" class="px-4 py-3">Check-in Date</th>
                            <th scope="col" class="px-4 py-3">Check-out Date</th>
                            <th scope="col" class="px-4 py-3">Archived Date</th>
                            <th scope="col" class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:loading.remove wire:target="search" class="text-left dark:bg-gray-700">
                        @forelse ($transactions as $transaction)
                            <tr
                                class="border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $transaction->transaction_number }}
                                </th>

                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $transaction->transactionUser->first_name }}
                                    {{ $transaction->transactionUser->last_name }}
                                </td>

                                <td class="px-4 py-3">
                                    @foreach ($transaction->properties as $property)
                                        {{ $property->name_number ?? 'N/A' }}<br>
                                    @endforeach
                                </td>

                                <td class="px-4 py-3">{{ $transaction->pax }}</td>

                                <td class="px-4 py-3">
                                    {{ $transaction->properties->first()?->pivot->days ?? 'N/A' }} day(s)
                                </td>

                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($transaction->end_datetime)->format('F j, Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $transaction->updated_at->format('F j, Y') }}
                                </td>

                                <td class="px-6 py-3 relative">
                                    <div x-data="dropdown()" x-init="init" class="relative">
                                        <button @click="toggle" :aria-expanded="open.toString()" aria-haspopup="true"
                                            class="text-gray-700 hover:text-blue-600 focus:outline-none dark:text-gray-200 dark:hover:text-blue-500">
                                            <i class="fas fa-ellipsis-h text-xl"></i>
                                        </button>

                                        <div x-show="open" x-ref="menu" @click.outside="open = false" x-transition
                                            :class="placement === 'top' ? 'bottom-full mb-2' : 'top-full mt-2'"
                                            class="absolute right-0 z-20 w-48 bg-white rounded-md shadow-lg border divide-y divide-gray-100 dark:bg-gray-700">
                                            <ul class="text-sm text-gray-700 dark:text-gray-200">
                                                <a href="{{ route('admin.view-reservation', ['transaction' => $transaction->id]) }}"
                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <i class="fas fa-eye mr-2 text-blue-600"></i> View Reservation
                                                </a>

                                                <a href="#"
                                                    wire:click.prevent="showRestoreModal({{ $transaction->id }}, '{{ $transaction->transaction_number }}')"
                                                    class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <i class="fas fa-undo mr-2 text-green-600"></i> Restore
                                                </a>

                                                {{-- Add Delete Action --}}
                                                <a href="#"
                                                    wire:click.prevent="showDeleteModal({{ $transaction->id }}, '{{ $transaction->transaction_number }}')"
                                                    class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <i class="fas fa-trash-alt mr-2"></i> Delete Permanently
                                                </a>

                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-500">
                                    No archived reservations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
            <div class="flex">
                <div class="flex space-x-4 items-center">
                    <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                    <select wire:model.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmingRestore" type="default">
        <x-slot name="title">
            {{ $restoreTitle }}
        </x-slot>

        <x-slot name="content">
            {{ $restoreMessage }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingRestore', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="restoreReservation" wire:loading.attr="disabled">
                Restore
            </x-button>
        </x-slot>
    </x-dialog-modal>


    <!-- Delete Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmingDelete" type="danger">
        <x-slot name="title">
            {{ $deleteTitle }}
        </x-slot>

        <x-slot name="content">
            {{ $deleteMessage }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingDelete', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteReservation" wire:loading.attr="disabled">
                {{ __('Delete Permanently') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>


</div>

<script>
    function dropdown() {
        return {
            open: false,
            placement: 'bottom',
            toggle() {
                this.open = !this.open;
            },
            init() {
                // Placement logic if needed
            }
        };
    }
</script>
