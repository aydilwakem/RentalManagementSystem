<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Room Rate') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
        ['label' => 'Rooms', 'url' => route('admin.rooms')],
        ['label' => 'View Room', 'url' => route('admin.view-room', ['room' => $room->id])],
        ['label' => 'View Room Rate', 'url' => route('admin.view-room-rate', ['roomRate' => $roomRate->id])],
        ['label' => 'Edit Room Rate', 'url' => route('admin.edit-room-rate', ['roomRate' => $roomRate->id])],
    ]" />
    </x-slot>

    <div class="mx-auto border rounded-lg p-6 max-w-3xl mb-6 mt-3 bg-white">

        <div class="relative flex justify-center items-center mb-4">
            <!-- Title -->
            <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Room Rate Details for:
                {{ $room->name_number }}
            </h2>

            <!-- Back Button -->
            <button onclick="history.back()"
                class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                <span class="leading-none translate-y-[-3px]">&times;</span>
            </button>
        </div>

        <form wire:submit.prevent="updateIndividualRoomRate">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Room Rate Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Rate Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" id="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                        placeholder="Ex. Rainy Day Rate, December Rate, etc.">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Start Date <span
                            class="text-red-500">*</span></label>
                    <input type="date" wire:model.live="start_date" id="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">End Date <span
                            class="text-red-500">*</span></label>
                    <input type="date" wire:model.live="end_date" id="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Adjusted Rate (by percentage) -->
                <div>
                    <label for="rate_percentage" class="block mb-2 text-sm font-medium text-gray-900">
                        Adjusted Rate <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-500">(Current Base Rate:
                            {{ number_format($room->amount, 2) }})</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <input type="number" wire:model="amount" id="amount" min="0" max="500000" onwheel="this.blur()"
                            class="text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
            focus:ring-green-600 focus:border-green-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 
            dark:text-white dark:placeholder-gray-400" placeholder="Ex. 10">
                    </div>

                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>




                <!-- Rate Type -->
                <div>
                    <label for="rate_type" class="block mb-2 text-sm font-medium text-gray-900">Rate Type <span
                            class="text-red-500">*</span></label>
                    <select wire:model="rate_type" id="rate_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        <option value="Weekdays">Weekdays</option>
                        <option value="Weekend">Weekend</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Peak">Peak</option>
                    </select>
                    @error('rate_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <textarea wire:model="description" id="description"
                        placeholder="Ex. Updated rate for peak season pricing"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400 resize-none" rows="3"></textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Freebies -->
                {{-- <div class="sm:col-span-2">
                    <label for="freebies" class="block mb-2 text-sm font-medium text-gray-900">Freebies</label>
                    <textarea wire:model="freebies" id="freebies"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400 resize-none" rows="3"></textarea>
                    @error('freebies')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Free Breakfast Inclusion
                    </label>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-800 dark:text-gray-200 text-sm">Not Included</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="freebies" id="freebies" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                    ">
                            </div>
                            <div
                                class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                        <span class="text-gray-800 dark:text-gray-200 text-sm">Included</span>
                    </div>
                </div>

                <!-- Is active -->
                <div class="sm:col-span-1">
                    <!-- Counter -->
                    {{-- <div class="flex flex-col">
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900">
                            Priority:
                        </label>

                        <!-- Counter Buttons -->
                        <div class="flex items-center space-x-1">
                            <button type="button" wire:click="decrement"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-l px-3 py-1">
                                -
                            </button>

                            <input type="number" wire:model="priority" min="1" max="10" onwheel="this.blur()"
                                class="text-center w-12 py-1 bg-white border border-gray-300 rounded focus:ring-green-600 focus:border-green-600" />

                            <button type="button" wire:click="increment"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-r px-3 py-1">
                                +
                            </button>
                        </div>

                        @error('priority')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                        Room Rate Status
                    </label>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-800 dark:text-gray-200 text-sm">Inactive</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_active" id="is_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:bg-green-600 transition
                                    ">
                            </div>
                            <div
                                class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                        <span class="text-gray-800 dark:text-gray-200 text-sm">Active</span>
                    </div>
                    @error('is_active')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror

                </div>

                <!-- Min Stay -->
                <div class="sm:col-span-1">
                    <label for="min_stay_nights" class="block mb-2 text-sm font-medium text-gray-900">Minimum Nights
                        Required <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="min_stay_nights" id="min_stay_nights" placeholder="Ex. 2 Nights"
                        onwheel="this.blur()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" min="1"
                        max="30">
                    @error('min_stay_nights')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Max Stay -->
                <div class="sm:col-span-1">
                    <label for="max_stay_nights" class="block mb-2 text-sm font-medium text-gray-900">Maximum Nights
                        Allowed <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="max_stay_nights" id="max_stay_nights" placeholder="Ex. 4 nights"
                        onwheel="this.blur()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 capitalize
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400" min="1"
                        max="90">
                    @error('max_stay_nights')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-ghost-button onclick="history.back()" type="button">
                    Cancel
                </x-ghost-button>
                <x-button type="submit" wire:loading.attr="disabled">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>
</div>