<div class="mx-4 sm:mx-auto bg-white dark:bg-[#2A2A2A] rounded-2xl p-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Maintenance Request') }}
        </h2>
    </x-slot>
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Maintenance</h2>


        <form wire:submit.prevent="">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Maintenance Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Maintenance Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter maintenance name" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Property ID -->
                <div>
                    <label for="property_id" class="block mb-2 text-sm font-medium text-gray-900">Assigned
                        Property</label>
                    <select wire:model="property_id" id="property_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        <option value="">Select Property</option>
                        @foreach ($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name_number }}</option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Maintenance Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Maintenance
                        Description</label>
                    <textarea wire:model="description" id="description"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 resize-none"
                        placeholder="Enter maintenance description" required></textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Reported At -->
                <div>
                    <label for="reported_at" class="block mb-2 text-sm font-medium text-gray-900">Reported
                        At</label>
                    <input type="date" wire:model="reported_at" id="reported_at"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('reported_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Resolved At -->
                <div>
                    <label for="resolved_at" class="block mb-2 text-sm font-medium text-gray-900">Resolved
                        At</label>
                    <input type="date" wire:model="resolved_at" id="resolved_at"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    @error('resolved_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div x-data="{ showPlanned: @entangle('priority_status') }" class="sm:col-span-2">
                    <!-- Priority Status -->
                    <div class="mb-4">
                        <label for="priority_status" class="block mb-2 text-sm font-medium text-gray-900">Priority
                            Status</label>
                        <select wire:model="priority_status" x-model="showPlanned" id="priority_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="">-- Select Priority Status -- </option>
                            <option value="emergency">Emergency</option>
                            <option value="urgent">Urgent</option>
                            <option value="routine">Routine</option>
                            <option value="planned">Planned</option>
                        </select>
                        @error('priority_status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <!-- Planned Date & Time Field -->
                        <div x-show="showPlanned === 'planned'" x-cloak class="sm:col-span-2">
                            <label for="planned_datetime" class="block mb-2 text-sm font-medium text-gray-900">Planned
                                Date
                                & Time</label>
                            <input type="datetime-local" wire:model="planned_datetime" id="planned_datetime"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            @error('planned_datetime')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit" wire:click="confirmEdit({{ $maintenance->id }})" wire:loading.attr="disabled">
                    Save Changes
                </x-button>
            </div>
        </form>
    <!-- Edit Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmEditItem">
        <x-slot name="title">
            {{ __('Edit Maintenance') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to save changes on this item?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-green text-white" wire:click="updateMaintenance({{ $maintenance->id }})"
                wire:loading.attr="disabled">
                {{ __('Edit Maintenance') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
