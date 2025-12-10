<div class="min-h-[550px] container mx-auto p-6 bg-white rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-white">

    <!-- Back Button -->
    <div class="mb-4">
        <button onclick="window.history.back();"
            class="inline-flex items-center text-gray-700 hover:text-gray-900 font-semibold focus:outline-none hover:underline dark:text-white dark:hover:text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Back to Day Tour Packages
        </button>
    </div>

    @if ($deletedDayTours->isEmpty())
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No deleted day tour yet.</p>
        </div>
    @else
        {{-- Display Session Message --}}
        @if (session('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ session('message') }}
            </div>
        @endif
        <div>
            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-x-auto border dark:border-gray-600">
                <!-- Table Body-->
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-700 bg-gray-200 dark:bg-green-100">
                        <tr>
                            <!-- Day Tour ID -->
                            <th scope="col" class="px-4 py-3 text-left">ID</th>

                            <!-- dayTour Name -->
                            <th scope="col" class="px-4 py-3 text-left">Day Tour Name</th>

                            <!-- dayTour Type -->
                            <th scope="col" class="px-4 py-3 text-left">Package Type</th>

                            <!-- Actions -->
                            <th scope="col" class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center dark:bg-gray-700 ">
                        @foreach ($deletedDayTours as $dayTour)
                            <tr class="border-b dark:border-gray-600 dark:hover:bg-gray-600">
                                <td class="px-4 py-3 text-left">{{ $dayTour->name ?? 'N/A' }}</td>
                                {{-- Day Tour Name --}}
                                <td class="px-4 py-3 text-left">
                                    {{ $dayTour->name ?? 'N/A' }}
                                </td>
                                {{-- Package Type (With/Without Room) --}}
                                <td class="px-4 py-3 text-left">
                                    @if ($dayTour->package_type == "with_room")
                                        <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-600">
                                            With Room
                                        </span>
                                    @else
                                        <span class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                            No Room
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 space-x-2 text-center">
                                    <x-button wire:click="restoreDayTour({{ $dayTour->id }})" class="mb-2">
                                        Restore
                                    </x-button>
                                    <!-- Delete Forever Button -->
                                    <x-danger-button wire:click="confirmDeleteForever({{ $dayTour->id }})">
                                        Delete Forever
                                    </x-danger-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Day Tour Forever') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to permanently delete this day tour? This action cannot be undone') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteDayTourForever" wire:loading.attr="disabled">
                        {{ __('Delete Day Tour') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>
        </div>
    @endif
</div>
