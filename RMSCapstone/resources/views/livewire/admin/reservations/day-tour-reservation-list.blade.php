<div class="min-h-[550px] container mx-auto p-6 max-w-full">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Day Tour Reservations') }}
        </h2>
    </x-slot>

    {{-- If there's no reservation, show this --}}
    @if ($transactions->isEmpty() && !$statusFilter && !$search)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No day tour reservations yet. <br> Click "Create Day Tour
                Reservation" to add a new reservation.</p>
            <x-button class="mt-4" href="{{ route('admin.create-day-tour-reservation') }}" icon="fas fa-plus">
                Create Day Tour Reservation
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

        <div class="mb-4">
            <div class="flex items-center justify-between">
                {{-- <div class="flex items-center space-x-2">
                    <!-- Export Today's Tours Button -->
                    @can('daytour-reservation-export')
                    <x-warning-button icon="fa fa-file" wire:click="exportToursToday">
                        Export Today's Day Tours
                    </x-warning-button>
                    @endcan
                </div> --}}

                {{-- <!-- Soft Deletes -->
                @can('daytour-reservation-soft-delete')
                <x-button class="!bg-gray-600 hover:!bg-gray-700 focus:ring focus:!ring-gray-600 focus:!ring-offset-2"
                    icon="fas fa-trash" href="{{ route('admin.deleted-daytour-transactions') }}">
                    Deleted Day Tours
                </x-button>
                @endcan --}}

                @can('')
                    <x-button icon="fas fa-plus" href="{{ route('admin.create-day-tour-reservation') }}">
                        New Transaction
                    </x-button>
                @endcan
            </div>
        </div>

        <!-- Table Container -->
        <div
            class="bg-white rounded-lg shadow-md border relative z-0 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
            <!-- Header-->
            <div class="flex items-center justify-between p-4 dark:bg-gray-800 rounded-lg">
                <!-- Search-->
                <div class="flex">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2
                                                                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search" required="">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="flex space-x-3">
                    <div class="flex space-x-3 items-center">
                        <label class="flex text-sm font-medium text-gray-900 dark:text-white">Reservation Status:</label>
                        <select wire:model.live="statusFilter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                                                                                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="">All</option>
                            <option value="pending">Awaiting Payment</option>
                            <option value="reserved">Pending Verification</option>
                            <option value="receipt_verified">Payment Verified</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="ongoing">On-going</option>
                            <option value="done">Completed</option>
                            <option value="no_show">No Show</option>
                            <option value="terminated">Terminated</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table Body-->
            <div wire:loading wire:target="search, statusFilter"
                class="w-full flex items-center justify-center min-h-[50px] relative mt-24">
                <div class="flex flex-col items-center justify-center text-center">
                    <!-- Spinner -->
                    <svg class="animate-spin h-6 w-6 text-green-700 mb-2" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12s5.373 12 12 12v-4a8 8 0 01-8-8z" />
                    </svg>
                    <span class="text-green-700 text-sm">Loading...</span>
                </div>
            </div>

            <div>
                <table class="min-w-full text-left">
                    <thead wire:loading.remove wire:target="search, statusFilter"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            {{-- Transaction Number --}}
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

                            {{-- Guest Name --}}
                            <th scope="col" class="px-4 py-3">Guest Name</th>

                            {{-- Email --}}
                            {{-- <th scope="col" class="px-4 py-3">Email</th> --}}

                            {{-- Contact --}}
                            {{-- <th scope="col" class="px-4 py-3">Contact</th> --}}

                            {{-- Tour Package --}}
                            <th scope="col" class="px-4 py-3">Tour Package</th>

                            {{-- Tour Date --}}
                            <th scope="col" class="px-4 py-3">Tour Date</th>

                            {{-- Guests --}}
                            <th scope="col" class="px-4 py-3">Total Pax</th>

                            {{-- Amount --}}
                            <th scope="col" class="px-4 py-3">Amount</th>

                            {{-- Status --}}
                            <th scope="col" class="px-4 py-3 justify-center">Status</th>

                            {{-- Action Buttons --}}
                            <th scope="col" class="px-4 py-3">Action</th>
                        </tr>
                    </thead>

                    <tbody wire:loading.remove wire:target="search, statusFilter" class="text-left dark:bg-gray-700">
                        @forelse ($transactions as $transaction)
                            <tr
                                class="border-b hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">
                                {{-- ID --}}
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <span>{{ $transaction->transaction_number }}</span>
                                </th>

                                {{-- Guest Name --}}
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $transaction->transactionUser->first_name }}
                                    {{ $transaction->transactionUser->last_name }}
                                </td>

                                {{-- Email --}}
                                {{-- <td class="px-4 py-3">
                                    {{ $transaction->transactionUser->email }}
                                </td> --}}

                                {{-- Contact --}}
                                {{-- <td class="px-4 py-3">
                                    {{ $transaction->transactionUser->contact_number }}
                                </td> --}}

                                {{-- Tour Package --}}
                                <td class="px-4 py-3">
                                    @if ($transaction->dayTour)
                                        {{ $transaction->dayTour->name }}
                                        @if ($transaction->dayTourRate)
                                            <span class="text-xs text-gray-500 block">
                                                {{ $transaction->dayTourRate->rate_name }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-red-500 text-sm italic">Day tour not found</span>
                                        @if(auth()->user()->can('admin'))
                                            <button wire:click="checkMissingDayTourData({{ $transaction->id }})"
                                                    class="text-xs text-blue-500 underline ml-1">
                                                Check
                                            </button>
                                        @endif
                                    @endif
                                </td>

                                {{-- Tour Date --}}
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($transaction->start_datetime)->format('F j, Y') }}
                                </td>

                                {{-- Guests --}}
                                <td class="px-4 py-3">
                                    {{ $transaction->pax }}
                                </td>

                                {{-- Amount --}}
                                <td class="px-4 py-3 font-medium">
                                    ₱{{ number_format($transaction->total_amount, 2) }}
                                </td>

                                {{-- Transaction Status --}}
                                <td class="px-4 py-2">
                                    @if ($transaction->transaction_status === 'pending')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">Awaiting
                                            Payment</span>
                                    @elseif ($transaction->transaction_status === 'reserved')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-500">Pending
                                            Verification</span>
                                    @elseif ($transaction->transaction_status === 'receipt_verified')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-500">Payment
                                            Verified</span>
                                    @elseif ($transaction->transaction_status === 'confirmed')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">Confirmed</span>
                                    @elseif ($transaction->transaction_status === 'ongoing')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">On-Going</span>
                                    @elseif ($transaction->transaction_status === 'done')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-indigo-200 text-indigo-600">Completed</span>
                                    @elseif ($transaction->transaction_status === 'no_show')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-pink-100 text-pink-500">No
                                            Show</span>
                                    @elseif ($transaction->transaction_status === 'terminated')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-600">Terminated</span>
                                    @elseif ($transaction->transaction_status === 'expired')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-500">Expired</span>
                                    @elseif ($transaction->transaction_status === 'cancelled')
                                        <span
                                            class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">Cancelled</span>
                                    @endif
                                </td>

                                {{-- Action Icons --}}
                                <td class="px-6 py-3 relative">
                                    <div x-data="dropdown()" x-init="init" class="relative">
                                        <!-- Trigger Button -->
                                        <button @click="toggle" :aria-expanded="open.toString()" aria-haspopup="true"
                                            class="text-gray-700 hover:text-blue-600 focus:outline-none dark:text-gray-200 dark:hover:text-blue-500">
                                            <i class="fas fa-ellipsis-h text-xl"></i>
                                        </button>

                                        <!-- Popover Menu -->
                                        <div x-show="open" x-ref="menu" @click.outside="open = false" x-transition
                                            :class="placement === 'top' ? 'bottom-full mb-2' : 'top-full mt-2'"
                                            class="absolute right-0 z-20 w-48 bg-white rounded-md shadow-lg border divide-y divide-gray-100 dark:bg-gray-700">
                                            <ul class="text-sm text-gray-700 dark:text-gray-200">
                                                <!--------------------- Safe Actions --------------------------------->
                                                <!-- View Icon -->
                                                @can('daytour-reservation-view')
                                                    <a href="{{ route('admin.view-daytour-reservation', ['transaction' => $transaction->id]) }}"
                                                        class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <i class="fas fa-eye mr-2 text-blue-600"></i> View Day Tour
                                                    </a>
                                                @endcan

                                                <!-- Confirm Receipt -->
                                                @if ($transaction->transaction_status === 'reserved')
                                                    <li>
                                                        <a href="{{ route('admin.view-daytour-reservation', ['transaction' => $transaction->id]) }}#payments"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                                            Confirm Receipt
                                                        </a>
                                                    </li>
                                                @endif

                                                <!-- Confirm Reservation -->
                                                @if ($transaction->transaction_status === 'receipt_verified')
                                                    <li>
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('confirmReservation', 'Confirm Day Tour', 'Are you sure you want to confirm this day tour?', {{ $transaction->id }}, 'default')"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                                            Confirm Day Tour
                                                        </a>
                                                    </li>
                                                @endif

                                                <!-- Start Day Tour -->
                                                @if ($transaction->transaction_status === 'confirmed')
                                                    <li>
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('startReservation', 'Start Day Tour', 'Are you sure you want to start this day tour?', {{ $transaction->id }}, 'default')"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-play-circle mr-2 text-green-600"></i>
                                                            Start Day Tour
                                                        </a>
                                                    </li>
                                                @endif

                                                <!-- Mark as Done -->
                                                @if ($transaction->transaction_status === 'ongoing')
                                                    <a href="#"
                                                        wire:click.prevent="showActionModal('markAsDone', 'Mark as Done', 'Are you sure you want to mark this day tour as Done?', {{ $transaction->id }}, 'default')"
                                                        class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <i class="fas fa-check-double mr-2 text-emerald-600"></i>
                                                        Mark as Done
                                                    </a>
                                                @endif

                                                <!-- Mark as No Show -->
                                                @if ($transaction->transaction_status === 'confirmed')
                                                    <li>
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('markNoShow', 'Mark as No Show', 'Are you sure you want to mark this day tour as No Show?', {{ $transaction->id }}, 'warning')"
                                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-user-slash mr-2 text-pink-600"></i>
                                                            Mark as No Show
                                                        </a>
                                                    </li>
                                                @endif

                                                <!--------------------- Destructive Actions -------------------------->
                                                <div class="border-t">
                                                    <!-- Cancel -->
                                                    @if ($transaction->transaction_status === 'reserved')
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('cancelReservation', 'Cancel Day Tour', 'Are you sure you want to cancel this day tour?', {{ $transaction->id }}, 'danger')"
                                                            class="flex items-center px-4 py-2 text-sm text-red-700 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                                                            <i class="fas fa-times mr-2"></i> Cancel Day Tour
                                                        </a>
                                                    @endif

                                                    <!-- Terminate -->
                                                    @if ($transaction->transaction_status === 'ongoing')
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('terminateReservation', 'Terminate Day Tour', 'Are you sure you want to terminate this day tour?', {{ $transaction->id }}, 'danger')"
                                                            class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            <i class="fas fa-ban mr-2"></i> Terminate Day Tour
                                                        </a>
                                                    @endif

                                                    <!-- Delete -->
                                                    @if (
                                                            $transaction->transaction_status === 'cancelled' ||
                                                            $transaction->transaction_status === 'expired' ||
                                                            $transaction->transaction_status === 'done' ||
                                                            $transaction->transaction_status === 'no_show' ||
                                                            $transaction->transaction_status === 'terminated'
                                                        )
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('deleteReservation', 'Delete Day Tour', 'Are you sure you want to delete this day tour?', {{ $transaction->id }}, 'danger')"
                                                            class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 ">
                                                            <i class="fas fa-trash-alt mr-2"></i> Delete Day Tour
                                                        </a>
                                                    @endif

                                                    <!-- Rollback Status -->
                                                    @if (
                                                            $transaction->transaction_status !== 'pending' &&
                                                            $transaction->transaction_status !== 'reserved' &&
                                                            $transaction->transaction_status !== 'expired' &&
                                                            $transaction->transaction_status !== 'receipt_verified'  // Add this condition
                                                        )
                                                        <a href="#"
                                                            wire:click.prevent="showActionModal('rollbackStatus', 'Undo Day Tour Status', 'Are you sure you want to undo the status of this day tour?', {{ $transaction->id }}, 'danger')"
                                                            class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                            <i class="fas fa-undo mr-2"></i> Undo Status
                                                        </a>
                                                    @endif
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-500">
                                    No day tour reservations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div>
                    @if ($cannotMarkAsDoneModal)
                        <x-dialog-modal wire:model.live="cannotMarkAsDoneModal" type="ghost">
                            <x-slot name="title">
                                {{ __('Cannot Perform Action') }}
                            </x-slot>

                            <x-slot name="content">
                                {{ __('Cannot mark this day tour as done. Invoice still has balance due.') }}
                            </x-slot>

                            <x-slot name="footer">
                                <x-secondary-button wire:click="$set('cannotMarkAsDoneModal', false)"
                                    wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </x-secondary-button>
                            </x-slot>
                        </x-dialog-modal>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            <div class="py-4 px-3 dark:bg-gray-800 dark:text-white rounded-lg">
                <div class="flex ">
                    <div class="flex space-x-4 items-center mb-3">
                        <label class="w-32 text-sm font-medium text-gray-900 dark:text-white">Per Page</label>
                        <select wire:model.live="perPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5
                                                                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
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

        <!-- Action Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingAction" :type="$actionButtonType">
            <x-slot name="title">
                {{ __($actionTitle) }}
            </x-slot>

            <x-slot name="content">
                {{ __($actionMessage) }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingAction', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                @if ($actionButtonType === 'danger')
                    <x-danger-button class="ms-3" wire:click="executeAction" wire:loading.attr="disabled">
                        {{ $actionTitle }}
                    </x-danger-button>
                @elseif ($actionButtonType === 'warning')
                    <x-warning-button class="ms-3" wire:click="executeAction" wire:loading.attr="disabled">
                        {{ $actionTitle }}
                    </x-warning-button>
                @else
                    <x-button class="ms-3" wire:click="executeAction" wire:loading.attr="disabled">
                        {{ $actionTitle }}
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>
    @endif
</div>

<script>
    function dropdown() {
        return {
            open: false,
            placement: 'bottom',
            init() {
                // Check if there's enough space below, otherwise place above
                const rect = this.$el.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                if (spaceBelow < 200) { // Adjust this value based on your dropdown height
                    this.placement = 'top';
                }
            },
            toggle() {
                this.open = !this.open;
            }
        }
    }
</script>
