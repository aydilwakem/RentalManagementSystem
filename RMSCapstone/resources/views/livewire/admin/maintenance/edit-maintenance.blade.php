<div class="shadow-lg rounded-lg p-6 bg-white max-w-2xl mx-auto">
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Maintenance</h2>
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li class="py-1">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form wire:submit.prevent="updateMaintenance">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Maintenance Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Maintenance
                            Description</label>
                        <textarea wire:model="description" id="description" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Enter maintenance description"></textarea>
                    </div>

                    <!-- Reported At -->
                    <div>
                        <label for="reported_at" class="block mb-2 text-sm font-medium text-gray-900">Reported
                            At</label>
                        <input type="date" wire:model="reported_at" id="reported_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Resolved At -->
                    <div>
                        <label for="resolved_at" class="block mb-2 text-sm font-medium text-gray-900">Resolved
                            At</label>
                        <input type="date" wire:model="resolved_at" id="resolved_at"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                    </div>

                    <!-- Priority Status -->
                    <div class="sm:col-span-2">
                        <label for="priority_status" class="block mb-2 text-sm font-medium text-gray-900">Priority
                            Status</label>
                        <select wire:model.defer="priority_status" id="priority_status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                            <option value="" disabled selected>Select Priority Status</option>
                            <option value="emergency">Emergency</option>
                            <option value="urgent">Urgent</option>
                            <option value="routine">Routine</option>
                            <option value="planned">Planned</option>
                        </select>
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                        Save Maintenance Changes
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>