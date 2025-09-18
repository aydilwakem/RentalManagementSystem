<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View Activity') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Activities', 'url' => route('admin.activities')],
            ['label' => 'View Activity', 'url' => route('admin.view-activity', ['activity' => $activity->id])],
        ]" />
    </x-slot>

    <div class="py-6">
        <div
            class="mx-auto max-w-5xl sm:px-6 lg:px-8 bg-white rounded-lg border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <!-- Back Button -->
            <div class="flex justify-end mb-4">
                <button onclick="history.back()"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Activity Image -->
                <div class="grid grid-cols-1 gap-2">
                    @php
                        // Normalize $activity->images into an array safely
                        $images = [];

                        if (is_string($activity->images)) {
                            $decoded = json_decode($activity->images, true);
                            $images = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($activity->images)) {
                            $images = $activity->images;
                        }
                    @endphp

                    @if (count($images) > 0)
                        <div class="w-full">
                            <img src="{{ asset('storage/' . $images[0]) }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Main Activity Image"
                                onclick="openModal('{{ asset('storage/' . $images[0]) }}')">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach (array_slice($images, 1) as $img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-44 object-cover rounded border cursor-pointer" alt="Activity Image"
                                    onclick="openModal('{{ asset('storage/' . $img) }}')">
                            @endforeach
                        </div>
                    @else
                        <div class="w-full">
                            <img src="{{ asset('images/rms-default.png') }}"
                                class="w-full h-72 object-cover rounded border cursor-pointer" alt="Default Image"
                                onclick="openModal('{{ asset('images/rms-default.png') }}')">
                        </div>
                    @endif
                    <!-- Image Popup View -->
                    <div id="imageModal" class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-80 hidden">
                        <div class="flex items-center justify-center min-h-screen">
                            <div class=" relative modal-content">
                                <img id="modalImg" src="" class="max-w-full max-h-[80vh] rounded-md">
                                <button onclick="closeModal()"
                                    class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none">
                                    <span class="leading-none translate-y-[-3px]">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 ">
                    <!-- Activity Name -->
                    <h2 class="mb-4 text-2xl md:text-3xl font-bold text-center text-gray-900 dark:text-white">
                        Activity: {{ $activity->name }}
                    </h2>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Description</h3>
                        <p class="text-gray-700 leading-relaxed dark:text-gray-200">
                            @if (!empty($activity->description))
                                {{ $activity->description }}
                            @else
                                <em class="text-gray-500 leading-relaxed italic dark:text-white">No description
                                    provided.</em>
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-2">Amount</h3>
                        <p class="text-green-700 font-medium dark:text-gray-200">
                            ₱{{ number_format($activity->amount, 2) }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-2">Inclusions</h3>
                        <p class="text-gray-700 leading-relaxed dark:text-gray-200">
                            @if (!empty($activity->inclusions))
                                {{ $activity->inclusions }}
                            @else
                                <em class="text-gray-500 leading-relaxed">No inclusions provided.</em>
                            @endif
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-2">Available Time Slots</h3>
                        <ul class="list-disc pl-5 text-gray-700 dark:text-gray-200">
                            @if (!empty($activity->available_times))
                                @foreach ($activity->available_times as $time)
                                    <li>{{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}</li>
                                @endforeach
                            @else
                                <li><em class="text-gray-500">No time slots provided.</em></li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 pt-2">
                <!-- Edit -->
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-activity', ['activity' => $activity->id]) }}">
                    Edit
                </x-ghost-button>

                <!-- Delete -->
                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $activity->id }})"
                    wire:loading.attr="disabled">
                    Delete
                </x-danger-button>
            </div>

            <!-- Delete Confirmation Modal -->
            <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
                <x-slot name="title">
                    {{ __('Delete Activity') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete this item?') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3" wire:click="deleteActivity({{ $activity->id }})"
                        wire:loading.attr="disabled">
                        {{ __('Delete Activity') }}
                    </x-danger-button>
                </x-slot>
            </x-dialog-modal>

            {{-- Cannot Delete Modal --}}
            <x-dialog-modal wire:model="cannotDeleteItem" type="ghost">
                <x-slot name="title">
                    {{ __('Unable to Delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('This activity is currently in use and cannot be deleted.') }}
                </x-slot>

                <x-slot name="footer">
                    <x-secondary-button wire:click="$set('cannotDeleteItem', false)" wire:loading.attr="disabled">
                        {{ __('OK') }}
                    </x-secondary-button>
                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
    <!-- Image Modal Script -->
    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore background scroll
        }
    </script>
</div>
