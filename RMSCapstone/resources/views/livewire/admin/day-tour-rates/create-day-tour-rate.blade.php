<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Create Day Tour Rate') }}
        </h2>
        <x-breadcrumbs :items="[
            ['label' => 'Day Tour Rates', 'url' => route('admin.day-tour-rates')],
            ['label' => 'Create Day Tour Rate', 'url' => route('admin.create-day-tour-rate')],
        ]" />
    </x-slot>

    <div class="py-3">
        <div
            class="mx-auto max-w-4xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:text-white dark:border-gray-600">

            <div class="relative flex items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Add New Day Tour Rate
                </h2>
                <button onclick="window.location.href='{{ route('admin.day-tour-rates') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <form wire:submit.prevent="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Day Tour Selection -->
                    <div>
                        <label for="day_tour_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Day Tour Package <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="day_tour_id" id="day_tour_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">Select Day Tour Package</option>
                            @foreach ($dayTours as $tour)
                                <option value="{{ $tour->id }}">{{ $tour->name }}</option>
                            @endforeach
                        </select>
                        @error('day_tour_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rate Name -->
                    <div>
                        <label for="rate_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Rate Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="rate_name" id="rate_name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Weekday Rate, Weekend Rate, Holiday Rate">
                        @error('rate_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Rate Type and Day Type -->
                    {{-- <div>
                        <label for="rate_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Rate Type <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="rate_type" id="rate_type" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="without_room">Without Room</option>
                            <option value="with_room">With Room</option>
                        </select>
                        @error('rate_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}





                    {{-- <div>
                        <label for="kid_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Kid Rate (₱) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="kid_rate" id="kid_rate" required step="0.01"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="0.00" min="0" onwheel="this.blur()">
                        @error('kid_rate')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <!-- Guest Range -->
                    {{-- <div>
                        <label for="min_guests" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Minimum Guests <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="min_guests" id="min_guests" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="1" min="1" onwheel="this.blur()">
                        @error('min_guests')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    {{-- <div>
                        <label for="max_guests" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Maximum Guests
                        </label>
                        <input type="number" wire:model="max_guests" id="max_guests"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Leave empty for no limit" min="1" onwheel="this.blur()">
                        @error('max_guests')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <!-- Adult and Kid Rates -->
                    <div>
                        <label for="adult_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Adult Rate <span class="text-red-500">*</span>
                            {{-- <span class="text-xs text-gray-500">(Current Base Rate: ₱{{ number_format($baseRate, 2)}} )</span> --}}
                        </label>
                        <input type="number" wire:model.live="adult_rate" id="adult_rate" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. ₱750.00" min="0" onwheel="this.blur()">
                        @error('adult_rate')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="kid_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Child Rate<span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="kid_rate" id="kid_rate" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. ₱650.00" min="0" onwheel="this.blur()">
                        @error('kid_rate')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="day_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Day Type <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="day_type" id="day_type" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="weekday">Weekday</option>
                            <option value="weekend">Weekend</option>
                            <option value="holiday">Holiday</option>
                        </select>
                        @error('day_type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Status
                        </label>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Inactive</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" value="1" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition">
                                </div>
                                <div
                                    class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                                </div>
                            </label>
                            <span class="text-gray-800 dark:text-gray-200 text-sm">Active</span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Additional Notes
                        </label>
                        <textarea wire:model="notes" id="notes" rows="3"
                            class="resize-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Any additional information about this rate..."></textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="window.location.href='{{ route('admin.day-tour-rates') }}'"
                        type="button">
                        Cancel
                    </x-ghost-button>

                    <x-button wire:click="confirmCreate">
                        Create Day Tour Rate
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Create Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmCreateItem">
            <x-slot name="title">
                {{ __('Create Day Tour Rate') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to create this day tour rate?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmCreateItem', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-button class="ms-3 bg-green text-white" wire:click="saveDayTourRate" wire:loading.attr="disabled">
                    {{ __('Create Day Tour Rate') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>
</div>
