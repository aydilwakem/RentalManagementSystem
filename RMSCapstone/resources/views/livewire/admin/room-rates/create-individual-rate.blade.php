<div class="border rounded-lg p-6 max-w-2xl mx-auto mb-6 mt-6">
    <div class="mx-auto max-w-2xl lg:py-2s">
        <h2 class="mb-4 text-xl font-bold text-gray-900">Add new room rate for {{$room->name}}</h2>

        <form wire:submit.prevent="saveIndividualRoomRate">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Room Rate Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Room Rate Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter room name">
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Start Date</label>
                    <input type="date" wire:model="start_date" id="start_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('start_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">End Date</label>
                    <input type="date" wire:model="end_date" id="end_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('end_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount</label>
                    <input type="number" wire:model="amount" id="amount"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter rate amount">
                    @error('amount')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Extra Person Charge -->
                <div>
                    <label for="extra_person_charge" class="block mb-2 text-sm font-medium text-gray-900">Extra
                        Person Charge</label>
                    <input type="number" wire:model="extra_person_charge" id="extra_person_charge"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter total amount">
                    @error('extra_person_charge')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Extended Stay Charge Per Hour -->
                <div>
                    <label for="extended_stay_charge_per_hr" class="block mb-2 text-sm font-medium text-gray-900">Extra
                        Stay Charge Per Hour</label>
                    <input type="number" wire:model="extended_stay_charge_per_hr" id="extended_stay_charge_per_hr"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter total amount">
                    @error('extended_stay_charge_per_hr')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rate Type -->
                <div class="sm:col-span-2 mb-3">
                    <label for="rate_type" class="block mb-2 text-sm font-medium text-gray-900">Rate Type</label>
                    <select wire:model="rate_type" id="rate_type"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="Weekdays">Weekdays</option>
                        <option value="Weekend">Weekend</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Peak">Peak</option>
                    </select>
                    @error('rate_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="sm:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                <textarea wire:model="description" id="description"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 resize-none"
                    rows="5"></textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

    </div>

    <div class="flex justify-between items-center space-y-2 mt-6">
        <x-button onclick="history.back()" type="button"
            class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
            Cancel
        </x-button>
        <x-button wire:loading.attr="disabled" wire:target="image">
            Add Room Rate
        </x-button>
    </div>
    </form>

</div>