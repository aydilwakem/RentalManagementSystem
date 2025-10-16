<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Day Tour Rate') }}
        </h2>
        <x-breadcrumbs :items="[
            ['label' => 'Day Tour Rates', 'url' => route('admin.day-tour-rates')],
            ['label' => 'View Day Tour Rate', 'url' => route('admin.view-day-tour-rate', ['dayTourRate' => $dayTourRate->id])],
        ]" />
    </x-slot>

    <div class="py-3 mb-4">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600">

            <!-- Title -->
            <div class="relative flex items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">
                    Day Tour Rate: {{ $dayTourRate->rate_name }}
                </h2>
                <button onclick="window.location.href='{{ route('admin.day-tour-rates') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Details Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4 border-b pb-2">
                        Rate Information
                    </h3>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li>
                            <strong>Day Tour Package:</strong>
                            <span class="ml-1">{{ $dayTourRate->dayTour->name ?? 'N/A' }}</span>
                        </li>
                        <li>
                            <strong>Rate Type:</strong>
                            <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $dayTourRate->rate_type === 'with_room' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $dayTourRate->rate_type_label }}
                            </span>
                        </li>
                        <li>
                            <strong>Day Type:</strong>
                            <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $dayTourRate->day_type === 'holiday' ? 'bg-red-100 text-red-800' :
                                   ($dayTourRate->day_type === 'weekend' ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                                {{ $dayTourRate->day_type_label }}
                            </span>
                        </li>
                        <li>
                            <strong>Adjusted Rate:</strong>
                            <span class="ml-1 font-medium">{{ $dayTourRate->formatted_adult_rate }}</span>
                        </li>
                        {{-- <li><strong>Kid Rate:</strong> {{ $dayTourRate->formatted_kid_rate }}</li> --}}
                        {{-- <li><strong>Guest Range:</strong> {{ $dayTourRate->guest_range }}</li> --}}
                    </ul>
                </div>

                <!-- Right Column -->
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-4 border-b pb-2">
                        Status & Notes
                    </h3>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-300">
                        <li>
                            <strong>Status:</strong>
                            @if ($dayTourRate->is_active)
                                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active <i class="fa-solid fa-check pl-1"></i>
                                </span>
                            @else
                                <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </li>
                        @if($dayTourRate->notes)
                            <li>
                                <strong>Notes:</strong>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border rounded-lg p-3 shadow-sm">
                                    {{ $dayTourRate->notes }}
                                </p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Pricing Summary (commented out) -->
            {{-- <div class="mt-8 p-5 bg-gray-50 rounded-lg dark:bg-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-3">Pricing Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <h4 class="font-medium text-gray-900 dark:text-white">Adult Rate</h4>
                        <p class="text-2xl font-bold text-green-600">{{ $dayTourRate->formatted_adult_rate }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">per adult</p>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <h4 class="font-medium text-gray-900 dark:text-white">Kid Rate</h4>
                        <p class="text-2xl font-bold text-green-600">{{ $dayTourRate->formatted_kid_rate }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">per kid</p>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-info-circle text-blue-500"></i>
                        Free for 2 years old & below
                    </p>
                </div>
            </div> --}}

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 mt-8">
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-day-tour-rate', ['dayTourRate' => $dayTourRate->id]) }}">
                    Edit
                </x-ghost-button>

                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $dayTourRate->id }})">
                    Delete
                </x-danger-button>
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

        {{-- Cannot Delete Modal --}}
        <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
            <x-slot name="title">
                {{ __('Unable to Delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('This day tour rate is currently in use and cannot be deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                    {{ __('OK') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
