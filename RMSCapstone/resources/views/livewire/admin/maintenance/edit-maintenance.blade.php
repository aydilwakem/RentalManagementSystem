<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Edit Maintenance Report') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Maintenance Requests', 'url' => route('admin.maintenances')],
            ['label' => 'View Maintenance Request', 'url' => route('admin.view-maintenance', ['maintenance' => $maintenance->id])],
            ['label' => 'Edit Maintenance Request', 'url' => route('admin.edit-maintenance', ['maintenance' => $maintenance->id])],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-2">
        <div
            class="mx-auto max-w-full sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-4">
                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-900 w-full text-center dark:text-white">Edit Maintenance Details
                </h2>

                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.maintenances') }}'" wire:navigate
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Form container -->
            <form wire:submit.prevent="">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Maintenance Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Leaky Faucet">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Property ID -->
                    <div>
                        <label for="property_id"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Assigned
                            Property <span class="text-red-500">*</span></label>
                        <select wire:model="property_id" id="property_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
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
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                            Description <span class="text-red-500">*</span></label>
                        <textarea wire:model="description" id="description" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5 resize-none
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400"
                            placeholder="Ex. Repair or replacement of components to stop water leakage from a faucet, preventing water waste and potential damage."></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Reported At -->
                    <div>
                        <label for="reported_at"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Date Reported
                            <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="reported_at" id="reported_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('reported_at')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Resolved At -->
                    <div>
                        <label for="resolved_at"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Date Resolved
                        </label>
                        <input type="date" wire:model="resolved_at" id="resolved_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('resolved_at')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ showPlanned: @entangle('priority_status') }" class="sm:col-span-2">
                        <!-- Priority Status -->
                        <div class="mb-4">
                            <label for="priority_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Priority
                                Status <span class="text-red-500">*</span></label>
                            <select wire:model="priority_status" x-model="showPlanned" id="priority_status"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                <option value="">Select Priority Status</option>
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
                                <label for="planned_datetime"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Planned
                                    Date
                                    & Time</label>
                                <input type="datetime-local" wire:model="planned_datetime" id="planned_datetime"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">

                                @error('planned_datetime')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center space-y-2 mt-6">
                    <x-ghost-button onclick="history.back()" type="button">
                        Cancel
                    </x-ghost-button>
                    <x-button type="submit" wire:click="confirmEdit({{ $maintenance->id }})"
                        wire:loading.attr="disabled">
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
                    {{ __('Are you sure you want to save changes to this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmEditItem', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-green text-white" wire:click="updateMaintenance({{ $maintenance->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Save Changes') }}
                    </x-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>
