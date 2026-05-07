<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Promo Code') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Promo Codes', 'url' => route('admin.promo-codes')],
            ['label' => 'View Promo Code', 'url' => route('admin.view-promo-code', ['promoCode' => $promoCode->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Code Name -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Code:
                    {{ $promoCode->code }}
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.view-promo-codes') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Promo Details -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Promo Details</h3>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Promo Code:</strong> {{ $promoCode->code }}</div>
                    <div><strong>Description:</strong> {{ $promoCode->description ?? 'No description provided' }}</div>
                    <div><strong>Discount Type:</strong> {{ ucfirst($promoCode->discount_type) }}</div>
                    <div><strong>Discount Value:</strong>
                        @if ($promoCode->discount_type == 'fixed')
                            ₱{{ number_format($promoCode->discount_value, 2) }}
                        @else
                            {{ number_format($promoCode->discount_value) }}%
                        @endif
                    </div>
                        <div><strong>Applied To:</strong> {{ $promoCode->propertyCategory?->name ?? 'All Categories' }}</div>

                </div>
            </div>

            <!-- Promo Limits -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Promo Limits</h3>
            <div
                class="bg-gray-50 rounded-lg p-6 mb-6 text-gray-600 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div><strong>Maximum Uses:</strong> {{ $promoCode->max_uses ?? 'No maximum limit' }} </div>
                    <div><strong>Current Total Number of Uses:</strong> {{ $promoCode->uses_count }} </div>
                    <div><strong>Limit Per User:</strong> {{ $promoCode->per_user_limit }} </div>
                    <div><strong>Minimum Booking Amount:
                        </strong>₱{{ number_format($promoCode->min_booking_amount, 2) }}
                    </div>
                </div>
            </div>

            <!-- Promo Duration -->
            <h3 class="text-lg font-bold text-green-800 mb-3 dark:text-green-300">Promo Duration</h3>
            <div
                class="bg-gray-50 rounded-lg p-6 mb-6 text-gray-600 dark:bg-gray-600 dark:text-gray-200 border dark:border-gray-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-gray-600 dark:text-gray-200">
                    <div>
                        @if ($promoCode->has_expiration)
                            <div><strong>Duration:</strong> {{ $promoCode->duration_days }} days</div>
                            <div><strong>Promo Start Date:</strong>
                                {{ \Carbon\Carbon::parse($promoCode->start_date)->format('F j, Y') }} </div>
                            <div><strong>Promo End Date:</strong>
                                {{ \Carbon\Carbon::parse($promoCode->end_date)->format('F j, Y') }} </div>
                            <div><strong>Promo Stay Start Date:</strong>
                                {{ \Carbon\Carbon::parse($promoCode->stay_start_date)->format('F j, Y') }} </div>
                            <div><strong>Promo Stay End Date:</strong>
                                {{ \Carbon\Carbon::parse($promoCode->stay_end_date)->format('F j, Y') }} </div>
                        @else
                            <strong>Validity:</strong> No Expiration
                        @endif
                    </div>
                    <div><strong>Promo Status:</strong>
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
                    </div>
                </div>
                {{-- <div><strong>Assigned Property:</strong> @php $hasProperty = false; @endphp
                    @foreach ($tenant->transactions as $transaction)
                    @foreach ($transaction->properties as $property)
                    {{ $property->name_number ?? 'N/A' }}<br>
                    @php $hasProperty = true; @endphp
                    @endforeach
                    @endforeach
                    @if (!$hasProperty)
                    <span class="italic text-gray-600">No property assigned</span>
                    @endif
                </div> --}}
            </div>


            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-auto mb-3">
                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-promo-code', ['promoCode' => $promoCode->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $promoCode->id }})">
                    Delete
                    </x-daanger-button>
            </div>

            {{-- Confirm Delete Modal --}}
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Promo Code') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this promo code?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deletePromoCode({{ $promoCode->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Promo Code') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This promo code is active lease and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
