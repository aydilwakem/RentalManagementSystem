<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('View  Maintenance Report') }}
        </h2>
        <!-- Navigation -->
        <x-breadcrumbs :items="[
            ['label' => 'Maintenance Requests', 'url' => route('admin.maintenances')],
            [
                'label' => 'View Maintenance Request',
                'url' => route('admin.view-maintenance', ['maintenance' => $maintenance->id]),
            ],
        ]" />
    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div
            class="mx-auto max-w-7xl sm:px-6 lg:px-8 bg-white rounded-xl border shadow-md p-6 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

            <div class="relative flex items-center mb-6">
                <!-- Back Button -->
                <button onclick="window.location.href='{{ route('admin.maintenances') }}'"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-2xl focus:outline-none absolute right-0 top-0 translate-y-[-12px]">
                    <span class="leading-none translate-y-[-3px]">&times;</span>
                </button>
            </div>

            <!-- Maintenance Name -->
            <h2
                class="text-2xl md:text-3xl font-bold leading-tight text-gray-800 text-center transform -translate-y-2 dark:text-white">
                {{ $maintenance->name }}
            </h2>

            <!-- Maintenance Details -->
            <div class="space-y-4">
                <!-- Assigned -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Property</h3>
                    <p class="text-gray-600 dark:text-gray-200">
                        {{ $maintenance->property->name_number ?? 'No Assigned Property' }}</p>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Description</h3>
                    <p class="text-gray-600 leading-relaxed dark:text-gray-200">{{ $maintenance->description }}</p>
                </div>

                <!-- Priority Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 dark:text-white">Priority Status</h3>
                    <p class="text-gray-600 dark:text-gray-200">
                        @if ($maintenance->priority_status === 'planned')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-600">
                                Planned
                            </span>
                        @elseif($maintenance->priority_status === 'routine')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-green-100 text-green-600">
                                Routine
                            </span>
                        @elseif($maintenance->priority_status === 'urgent')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-600">
                                Urgent
                            </span>
                        @elseif($maintenance->priority_status === 'emergency')
                            <span
                                class="inline-block py-1 px-2 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                Emergency
                            </span>
                        @endif
                    </p>
                </div>

                <!-- Report At and Resolved At Dates -->
                {{-- <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 dark:text-white">Report and Resolution Dates
                    </h3>
                    <div class="overflow-x-auto w-full">
                        <div class="mx-auto">
                            <table
                                class="w-full table-fixed divide-y divide-gray-200 border dark:border-gray-500 dark:divide-gray-500">
                                <thead class="bg-gray-100 dark:bg-green-200">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider w-1/2">
                                            Reported At
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider w-1/2">
                                            Resolved At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-500">
                                    <tr>
                                        <!-- Reported At -->
                                        <td
                                            class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white align-top">
                                            <div class="flex flex-col items-center space-y-1">
                                                <span>{{ $maintenance->reported_at->format('F j, Y') }}</span>
                                                <button type="button"
                                                    class="text-green-600 hover:underline text-sm font-medium"
                                                    onclick="openMaintenanceModal({{ $maintenance->id }}); return false;">
                                                    View reported images
                                                </button>
                                            </div>
                                        </td>

                                        <!-- Resolved At -->
                                        <td
                                            class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white align-top">
                                            @if ($maintenance->resolved_at)
                                                <div class="flex flex-col items-center space-y-1">
                                                    <span>{{ $maintenance->resolved_at->format('F j, Y') }}</span>
                                                    <button type="button"
                                                        class="text-green-600 hover:underline text-sm font-medium"
                                                        onclick="openMaintenanceModal({{ $maintenance->id }}); return false;">
                                                        View resolved images
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-gray-500 italic dark:text-gray-300">Not yet
                                                    resolved</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Modal -->
                            <div id="modal-maintenance-{{ $maintenance->id }}"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div
                                    class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-4 relative max-h-[80vh] overflow-y-auto">
                                    <button type="button" onclick="closeMaintenanceModal({{ $maintenance->id }})"
                                        class="absolute top-2 right-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-full w-7 h-7 flex items-center justify-center text-2xl focus:outline-none">
                                        <span
                                            class="w-full h-full flex items-center justify-center pointer-events-none mb-1">&times;</span>
                                    </button>

                                    <!-- Carousel -->
                                    @php
                                        $reportedImages = $maintenance->maintenance_images ?? [];
                                        $resolvedImages = $maintenance->resolved_images ?? [];
                                    @endphp

                                    @if (count($reportedImages))
                                        <h2 class="text-lg font-bold mb-3 mt-2 text-center text-green-800">Before / Reported Images
                                        </h2>
                                        <div class="relative mb-6 px-12">
                                            <img id="reported-img-{{ $maintenance->id }}"
                                                src="{{ asset('storage/' . $reportedImages[0]) }}"
                                                class="w-full h-full object-cover rounded-lg shadow" />

                                            @if (count($reportedImages) > 1)
                                                <!-- Prev Button -->
                                                <button
                                                    onclick="prevImage('{{ $maintenance->id }}', 'reported', {{ count($reportedImages) }})"
                                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-200 px-2 py-1 rounded-full">
                                                    <i class="fa-solid fa-chevron-left"></i>
                                                </button>

                                                <!-- Next Button -->
                                                <button
                                                    onclick="nextImage('{{ $maintenance->id }}', 'reported', {{ count($reportedImages) }})"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-200 px-2 py-1 rounded-full">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Resolved Images Carousel -->
                                    @if (count($resolvedImages))
                                        <h2 class="text-lg font-semibold mb-2 text-center">After / Resolved Images</h2>
                                        <div class="relative mb-6 px-12">
                                            <img id="resolved-img-{{ $maintenance->id }}"
                                                src="{{ asset('storage/' . $resolvedImages[0]) }}"
                                                class="w-full h-80 object-contain rounded-lg shadow" />

                                            @if (count($resolvedImages) > 1)
                                                <!-- Prev Button -->
                                                <button
                                                    onclick="prevImage('{{ $maintenance->id }}', 'resolved', {{ count($resolvedImages) }})"
                                                    class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-200 px-2 py-1 rounded-full">
                                                    <i class="fa-solid fa-chevron-left"></i>
                                                </button>

                                                <!-- Next Button -->
                                                <button
                                                    onclick="nextImage('{{ $maintenance->id }}', 'resolved', {{ count($resolvedImages) }})"
                                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-200 px-2 py-1 rounded-full">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 dark:text-white">
                        Report and Resolution Details
                    </h3>

                    <div class="overflow-x-auto w-full">
                        <div class="mx-auto">
                            <table
                                class="w-full table-fixed divide-y divide-gray-200 border dark:border-gray-500 dark:divide-gray-500">
                                <thead class="bg-green-50 dark:bg-green-200">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Reported At</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider">
                                            Resolved At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-500">
                                    <tr>
                                        <!-- Reported -->
                                        <td
                                            class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white align-top mb-8">
                                            <div class="flex flex-col items-center space-y-2">
                                                <span class="font-semibold mb-2">{{ $maintenance->reported_at->format('F j, Y') }}</span>

                                                <!-- Images Dropdown -->
                                                <div x-data="{ open: false }" class="relative my-4 w-full">
                                                    <div @click="open = !open"
                                                        class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded-md cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600">
                                                        <div
                                                            class="flex items-center space-x-2 text-gray-800 dark:text-white">
                                                            <i class="fa-solid fa-images"></i>
                                                            <span class="font-medium">Reported Images</span>
                                                        </div>
                                                        <svg :class="{ 'rotate-180': open }"
                                                            class="w-4 h-4 transform transition-transform duration-200 text-gray-600 dark:text-gray-200"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>

                                                    <!-- Image Preview -->
                                                    <div x-show="open" x-cloak
                                                        class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-md shadow-md">
                                                        @foreach ($maintenance->maintenance_images ?? [] as $image)
                                                            <img src="{{ asset('storage/' . $image) }}"
                                                                class="cursor-pointer rounded-md object-cover w-full h-40 shadow border border-gray-300"
                                                                @click="openModal('{{ asset('storage/' . $image) }}')" />
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Resolved -->
                                        <td
                                            class="px-6 py-4 text-center text-sm text-gray-900 dark:text-white align-top">
                                            <div class="flex flex-col items-center space-y-2">
                                                @if ($maintenance->resolved_at)
                                                    <span class="font-semibold mb-2">{{ $maintenance->resolved_at->format('F j, Y') }}</span>

                                                    <!-- Resolved Images Dropdown -->
                                                    @if (count($maintenance->resolved_images ?? []))
                                                        <div x-data="{ open: false }" class="relative my-4 w-full">
                                                            <div @click="open = !open"
                                                                class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded-md cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600">
                                                                <div
                                                                    class="flex items-center space-x-2 text-gray-800 dark:text-white">
                                                                    <i class="fa-solid fa-check-circle"></i>
                                                                    <span class="font-medium">Resolved Images</span>
                                                                </div>
                                                                <svg :class="{ 'rotate-180': open }"
                                                                    class="w-4 h-4 transform transition-transform duration-200 text-gray-600 dark:text-gray-200"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>

                                                            <!-- Image Previews -->
                                                            <div x-show="open" x-cloak
                                                                class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-md shadow-md">
                                                                @foreach ($maintenance->resolved_images ?? [] as $image)
                                                                    <img src="{{ asset('storage/' . $image) }}"
                                                                        class="cursor-pointer rounded-md object-cover w-full h-40 shadow border border-gray-300"
                                                                        @click="openModal('{{ asset('storage/' . $image) }}')" />
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-500 italic dark:text-gray-300">Not yet
                                                        resolved</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Collapsible Sections -->
                            @php
                                $reportedImages = $maintenance->maintenance_images ?? [];
                                $resolvedImages = $maintenance->resolved_images ?? [];
                            @endphp

                            @if (count($reportedImages))
                                <div id="reported-images-{{ $maintenance->id }}"
                                    class="hidden mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($reportedImages as $image)
                                        <img src="{{ asset('storage/' . $image) }}"
                                            class="cursor-pointer w-full h-40 object-cover rounded-lg shadow"
                                            onclick="openModal('{{ asset('storage/' . $image) }}')" />
                                    @endforeach
                                </div>
                            @endif

                            @if (count($resolvedImages))
                                <div id="resolved-images-{{ $maintenance->id }}"
                                    class="hidden mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($resolvedImages as $image)
                                        <img src="{{ asset('storage/' . $image) }}"
                                            class="cursor-pointer w-full h-40 object-cover rounded-lg shadow"
                                            onclick="openModal('{{ asset('storage/' . $image) }}')" />
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Popup Modal -->
                <div id="imageModal"
                    class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden flex items-center justify-center">
                    <div class="relative">
                        <button onclick="closeModal()"
                            class="absolute top-0 right-0 m-4 text-white text-3xl font-bold">&times;</button>
                        <img id="modalImg" src="" class="max-h-[70vh] max-w-[70vw] rounded-lg shadow-xl" />
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between space-x-4 pt-4 mt-auto">
                <x-ghost-button type="button" icon="fas fa-pen-to-square" wire:navigate
                    href="{{ route('admin.edit-maintenance', ['maintenance' => $maintenance->id]) }}">
                    Edit
                </x-ghost-button>

                <x-danger-button type="button" icon="fas fa-trash" wire:click="confirmDelete({{ $maintenance->id }})"
                    wire:loading.attr="disabled">
                    Delete
                </x-danger-button>
            </div>

        </div>

        <!-- Delete Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmItemDelete" type="danger">
            <x-slot name="title">
                {{ __('Delete Maintenance') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this item?') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmItemDelete', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteMaintenanceItem({{ $maintenance->id }})"
                    wire:loading.attr="disabled">
                    {{ __('Delete Maintenance') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </div>

    <!-- Maintenance Images Modal -->
    {{-- <script>
        const reportedImages = {
            {{ $maintenance->id }}: {!! collect($reportedImages)->map(fn($img) => asset('storage/' . $img))->values()->toJson() !!}
        };

        const resolvedImages = {
            {{ $maintenance->id }}: {!! collect($resolvedImages)->map(fn($img) => asset('storage/' . $img))->values()->toJson() !!}
        };

        const reportedIndexes = {};
        const resolvedIndexes = {};

        function openMaintenanceModal(maintenanceId) {
            document.getElementById('modal-maintenance-' + maintenanceId).classList.remove('hidden');

            // Initialize indexes if not yet set
            if (reportedIndexes[maintenanceId] === undefined) reportedIndexes[maintenanceId] = 0;
            if (resolvedIndexes[maintenanceId] === undefined) resolvedIndexes[maintenanceId] = 0;

            updateImage(maintenanceId, 'reported', reportedIndexes[maintenanceId]);
            updateImage(maintenanceId, 'resolved', resolvedIndexes[maintenanceId]);
        }

        function closeMaintenanceModal(maintenanceId) {
            document.getElementById('modal-maintenance-' + maintenanceId).classList.add('hidden');
        }

        function prevImage(maintenanceId, type, total) {
            if (type === 'reported') {
                reportedIndexes[maintenanceId] = (reportedIndexes[maintenanceId] - 1 + total) % total;
                updateImage(maintenanceId, type, reportedIndexes[maintenanceId]);
            } else {
                resolvedIndexes[maintenanceId] = (resolvedIndexes[maintenanceId] - 1 + total) % total;
                updateImage(maintenanceId, type, resolvedIndexes[maintenanceId]);
            }
        }

        function nextImage(maintenanceId, type, total) {
            if (type === 'reported') {
                reportedIndexes[maintenanceId] = (reportedIndexes[maintenanceId] + 1) % total;
                updateImage(maintenanceId, type, reportedIndexes[maintenanceId]);
            } else {
                resolvedIndexes[maintenanceId] = (resolvedIndexes[maintenanceId] + 1) % total;
                updateImage(maintenanceId, type, resolvedIndexes[maintenanceId]);
            }
        }

        function updateImage(maintenanceId, type, index) {
            const imgId = `${type}-img-${maintenanceId}`;
            const imgElement = document.getElementById(imgId);
            if (!imgElement) return;

            const imageList = type === 'reported' ? reportedImages[maintenanceId] : resolvedImages[maintenanceId];
            if (imageList && imageList[index]) {
                imgElement.src = imageList[index];
            }
        }
    </script> --}}
    <script>
        function toggleImageSection(id) {
            const section = document.getElementById(id);
            if (section) {
                section.classList.toggle('hidden');
            }
        }

        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImg');
            modalImg.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>

</div>
