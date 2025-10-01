<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    {{-- Display Session Message --}}
    @if (session('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg
            {{ session('alert-type') === 'success' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
            {{ session('message') }}
        </div>
    @endif
    <div>
        <div class="bg-white rounded-lg dark:bg-gray-800 dark:text-white">

            <!-- Search bar + Buttons -->
            <div class="flex justify-between items-center gap-4 mb-6 flex-wrap">
                <!-- Search Input -->
                <div class="relative w-full max-w-sm flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500 dark:text-gray-300"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Search" />
                </div>

                <div class="ml-auto">
                    <label for="view_mode" class=" mb-2 text-sm text-gray-900 dark:text-gray-200">View Mode:</label>
                    <select id="view_mode" wire:model.live="viewMode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white w-20">
                        <option value="cards">Cards</option>
                        <option value="table">Table</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 flex-none">
                    <button wire:click="exportLogsToPDF"
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm">
                        <i class="fa fa-file-export mr-1"></i> Export Logs
                    </button>
                    <button wire:click="resetFilters"
                        class="px-4 py-2 text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg text-sm">
                        <i class="fa fa-redo mr-1"></i> Reset Filters
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="flex items-center justify-between p-4">
                <div class="flex gap-4 w-full">
                    <div class="relative w-full">
                        {{-- Start Date --}}
                        <div class="w-full">
                            <label for="start_date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                Start Date</label>
                            <input type="date" wire:model="start_date" id="start_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('start_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- End Date --}}
                    <div class="w-full">
                        <label for="end_date"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">End
                            Date
                        </label>
                        <input type="date" wire:model="end_date" id="end_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                                                                                                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                        @error('end_date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Log Name Filter --}}
                    <div class="w-full">
                        <label for="audit_log_name_filter"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Filter by
                            Module:</label>
                        <select id="audit_log_name_filter" name="audit_log_name_filter"
                            wire:model="audit_log_name_filter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                                                                                                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">All</option>
                            @foreach ($logNames as $logName)
                                <option value="{{ $logName }}">{{ ucfirst(str_replace('_', ' ', $logName)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Event Filter -->
                    <div class="w-full">
                        <label for="audit_event_filter"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Filter by
                            Event:</label>
                        <select id="audit_event_filter" name="audit_event_filter" wire:model="audit_event_filter"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                                                                                                            dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            <option value="">All</option>
                            @foreach ($eventTypes as $event)
                                <option value="{{ $event }}">{{ ucfirst(str_replace('_', ' ', $event)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <x-button icon="fa fa-filter" wire:click="apply_audit_log_filter">
                            Apply Filter
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>

            @if ($viewMode === 'cards')
                {{-- Timeline View --}}
                <div class="relative ml-5">

                    @forelse($logs->groupBy(fn($log) => $log->created_at->format('M d')) as $date => $logsByDate)
                        {{-- Date label --}}


                        <div class="relative mb-8">
                            <div
                                class="absolute -left-5 top-0 text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded dark:bg-gray-600 dark:text-white z-20">
                                {{ $logsByDate->first()->created_at->format('F d, Y') }}
                            </div>

                            <div class="border-l-2 border-gray-200 dark:border-gray-600 pl-4 pt-8">
                                @foreach ($logsByDate as $log)
                                    @php
                                        $properties = $log->properties ?? [];
                                        $attributes = $properties['attributes'] ?? [];
                                        $old = $properties['old'] ?? [];

                                        $event = $log->event;
                                        $collapseId = 'collapse-' . $log->id;

                                        $hasChanges = match ($event) {
                                            'created' => !empty($attributes),
                                            'deleted' => !empty($old),
                                            'updated' => !empty($old) && !empty($attributes),
                                            default => false,
                                        };
                                    @endphp

                                    <div class="relative mb-8 flex items-start">
                                        <div class="absolute -left-9 top-0">
                                            <div
                                                class="w-10 h-10 flex items-center justify-center rounded-full z-10
                                            @if (Str::contains($log->description, 'created')) bg-green-100 text-green-600
                                            @elseif(Str::contains($log->description, 'updated')) bg-yellow-100 text-yellow-600
                                            @elseif(Str::contains($log->description, 'deleted')) bg-red-100 text-red-600
                                            @elseif(Str::contains($log->description, 'logged in')) bg-blue-100 text-blue-600
                                            @elseif(Str::contains($log->description, 'logged out')) bg-purple-100 text-purple-600
                                            @else bg-gray-100 text-gray-600 @endif">
                                                @if (Str::contains($log->description, 'created'))
                                                    <i class="fas fa-plus"></i>
                                                @elseif(Str::contains($log->description, 'updated'))
                                                    <i class="fas fa-edit"></i>
                                                @elseif(Str::contains($log->description, 'deleted'))
                                                    <i class="fas fa-trash"></i>
                                                @elseif (Str::contains($log->description, 'logged in'))
                                                    <i class="fas fa-sign-in-alt"></i>
                                                @elseif (Str::contains($log->description, 'logged out'))
                                                    <i class="fas fa-sign-out-alt"></i>
                                                @else
                                                    <i class="fas fa-info-circle"></i>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-1 bg-white dark:bg-gray-700 rounded-lg shadow p-4 ml-4">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-green-700 dark:text-white">
                                                    {{ $log->log_name ?? 'general' }}
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $log->created_at->format('F d, Y g:i A') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                {{ $log->description }} by
                                                <span
                                                    class="inline-block py-1 px-2 rounded-md text-sm font-semibold bg-gray-100 text-gray-600">
                                                    @if ($log->causer)
                                                        {{ $log->causer->name }} {{ $log->causer->last_name }}
                                                    @else
                                                        System
                                                    @endif
                                                </span>
                                            </p>

                                            <div class="text-xs text-gray-400 mt-2 flex items-center">
                                                <i class="fas fa-clock mr-1 text-gray-400"></i>
                                                {{ $log->created_at->diffForHumans() }}
                                            </div>

                                            @if ($hasChanges)
                                                <button type="button"
                                                    class="mt-3 text-blue-600 hover:underline text-sm"
                                                    onclick="document.getElementById('{{ $collapseId }}').classList.toggle('hidden')">
                                                    View Changes
                                                </button>

                                                <div id="{{ $collapseId }}"
                                                    class="hidden mt-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded p-3 text-sm">

                                                    @if ($log->event === 'created')
                                                        <p class="font-semibold text-blue-600">Created with:</p>
                                                        <ul class="list-disc list-inside space-y-1">
                                                            @foreach ($attributes as $key => $val)
                                                                <li>
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                    "<span
                                                                        class="text-green-600">{{ is_array($val) ? json_encode($val) : $val ?? 'N/A' }}</span>"
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif ($log->event === 'deleted')
                                                        <p class="font-semibold text-red-600">Deleted values:</p>
                                                        <ul class="list-disc list-inside space-y-1">
                                                            @foreach ($old as $key => $val)
                                                                <li>
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                    "<span
                                                                        class="text-red-500">{{ is_array($val) ? json_encode($val) : $val ?? 'N/A' }}</span>"
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @elseif ($log->event === 'updated')
                                                        <p class="font-semibold text-yellow-600">Updated fields:</p>
                                                        <ul class="list-disc list-inside space-y-1">
                                                            @foreach ($attributes as $key => $newVal)
                                                                @php
                                                                    $oldVal = $old[$key] ?? null;
                                                                @endphp
                                                                @if ($oldVal != $newVal)
                                                                    <li>
                                                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                        "<span
                                                                            class="text-red-500">{{ is_array($oldVal) ? json_encode($oldVal) : $oldVal ?? 'N/A' }}</span>"
                                                                        was updated to
                                                                        "<span
                                                                            class="text-green-600">{{ is_array($newVal) ? json_encode($newVal) : $newVal ?? 'N/A' }}</span>"
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-300 py-10">
                            <i class="fas fa-info-circle mr-2"></i>No logs found.
                        </div>
                    @endforelse
                </div>
            @elseif ($viewMode === 'table')
                {{-- Table View --}}

                <table class="min-w-full text-sm text-left text-gray-700 dark:text-white">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Log Name</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Causer</th>
                            <th class="px-4 py-2">Log Timestamp</th>
                            <th class="px-4 py-2">Changes</th> {{-- New column --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @php
                                $properties = $log->properties ?? [];
                                $attributes = $properties['attributes'] ?? [];
                                $old = $properties['old'] ?? [];

                                $event = $log->event;

                                $hasChanges = match ($event) {
                                    'created' => !empty($attributes),
                                    'deleted' => !empty($old),
                                    'updated' => !empty($old) && !empty($attributes),
                                    default => false,
                                };
                            @endphp

                            <tr class="border-b border-gray-300 dark:border-gray-600">
                                <td class="px-4 py-2">{{ $log->created_at->format('F d, Y') }}</td>
                                <td class="px-4 py-2">{{ $log->log_name ?? 'general' }}</td>
                                <td class="px-4 py-2">{{ $log->description }}</td>
                                <td class="px-4 py-2">
                                    @if ($log->causer)
                                        {{ $log->causer->name }} {{ $log->causer->last_name }}
                                    @else
                                        System
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $log->created_at->format('g:i A') }}</td>
                                <td class="px-4 py-2">
                                    @if ($hasChanges)
                                        <button wire:click="toggleLogChanges({{ $log->id }})"
                                            class="text-blue-600 hover:underline text-sm">
                                            {{ in_array($log->id, $expandedLogs) ? 'Hide Changes' : 'View Changes' }}
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">No changes</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Expanded Changes Row --}}
                            @if (in_array($log->id, $expandedLogs) && $hasChanges)
                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td colspan="6" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">
                                        @if ($log->event === 'created')
                                            <p class="font-semibold text-blue-600 mb-1">Created with:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($attributes as $key => $val)
                                                    <li>
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                        "{{ is_array($val) ? json_encode($val) : $val ?? 'N/A' }}"
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif ($log->event === 'deleted')
                                            <p class="font-semibold text-red-600 mb-1">Deleted values:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($old as $key => $val)
                                                    <li>
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                        "{{ is_array($val) ? json_encode($val) : $val ?? 'N/A' }}"
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif ($log->event === 'updated')
                                            <p class="font-semibold text-yellow-600 mb-1">Updated fields:</p>
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($attributes as $key => $newVal)
                                                    @php
                                                        $oldVal = $old[$key] ?? null;
                                                    @endphp
                                                    @if ($oldVal != $newVal)
                                                        <li>
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                            "<span
                                                                class="text-red-500">{{ is_array($oldVal) ? json_encode($oldVal) : $oldVal ?? 'N/A' }}</span>"
                                                            was updated to
                                                            "<span
                                                                class="text-green-600">{{ is_array($newVal) ? json_encode($newVal) : $newVal ?? 'N/A' }}</span>"
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-300">
                                    No logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @endif

            <!-- Pagination and Per Page -->
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-3">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Per Page</label>
                    <select wire:model.live="perPage"
                        class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg p-2 w-24 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
