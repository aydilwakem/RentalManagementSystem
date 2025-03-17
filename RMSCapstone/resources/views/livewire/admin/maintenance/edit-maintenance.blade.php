<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Maintenance Request') }}
        </h2>
    </x-slot>
    <div class="shadow-lg rounded-lg p-6 max-w-2xl mx-auto border mb-4 mt-4 bg-white">
        <h2 class="mb-4 text-xl font-bold text-gray-900 text-center">Edit Maintenance</h2>


        <form wire:submit.prevent="updateMaintenance">
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <!-- Maintenance Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Maintenance Name</label>
                    <input type="text" wire:model="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter maintenance name" required>
                    @error('name')
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

                <!-- Priority Status -->
                <div class="sm:col-span-2">
                    <label for="priority_status" class="block mb-2 text-sm font-medium text-gray-900">Priority
                        Status</label>
                    <select wire:model.defer="priority_status" id="priority_status"
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

            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center space-y-2 mt-6">
                <x-button onclick="history.back()" type="button"
                    class="!bg-gray-200 !text-black hover:!bg-gray-300 focus:!ring-2 focus:!ring-gray-400 focus:!outline-none">
                    Cancel
                </x-button>
                <x-button type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </div>

</div>