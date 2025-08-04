<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    @if ($logs->isEmpty() && !$search)
        <!-- Empty Page Message -->
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg font-semibold">No logs yet.</p>
        </div>
    @else
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
                <!-- Search bar -->
                <div class="flex justify-between items-center mb-6">
                    <div class="relative w-full max-w-xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500 dark:text-gray-300"></i>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search" />
                    </div>
                </div>

                <div class="flex items-center justify-between p-4">
                    <div class="flex gap-4 w-full">
                        <div class="relative w-full">
                            {{-- Start Date --}}
                            <div class="w-full">
                                <label for="start_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">
                                    Start Date</label>
                                <input type="date" wire:model.lazy="start_date" id="start_date"
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
                            <input type="date" wire:model.lazy="end_date" id="end_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                            @error('end_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Select A Module --}}
                        <div class="w-full">
                            <label for="select_module"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Filter by Module
                            </label>
                            <select wire:model.live="select_module"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                <option value="">All Modules</option>
                                {{-- @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name_number }}</option>
                                @endforeach --}}

                            </select>
                        </div>

                        <!-- Audit Log Filter -->
                        <div class="w-full">
                            <label for="audit_log_filter"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Audit Logs
                                Filter:</label>
                            <select id="audit_log_filter" name="audit_log_filter" wire:model.live="audit_log_filter"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-600 focus:border-green-600 block w-full p-2.5
                                                    dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:placeholder-gray-400">
                                <option value="">All</option>
                                <option value="created">Created</option>
                                <option value="updated">Updated</option>
                                <option value="deleted">Deleted</option>
                                <option value="logged_in">Logged In</option>
                                <option value="logged_out">Logged Out</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-button icon="fa fa-filter" wire:click="apply_audit_log_filter">
                                Apply Filter
                            </x-button>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="relative ml-5">
                    @forelse($logs->groupBy(fn($log) => $log->created_at->format('M d')) as $date => $logsByDate)
                        {{-- Date label --}}
                        <div class="relative mb-8">
                            <div
                                class="absolute -left-5 top-0 text-xs text-gray-500 bg-green-50 px-2 py-1 rounded dark:bg-gray-600 dark:text-white z-20">
                                {{ $logsByDate->first()->created_at->format('F d, Y') }}
                            </div>
                            <div class="border-l-2 border-gray-200 dark:border-gray-600 pl-4 pt-8">
                                @foreach ($logsByDate as $log)
                                    <div class="relative mb-8 flex items-start">
                                        <div class="absolute -left-9 top-0">
                                            <div
                                                class="w-10 h-10 flex items-center justify-center rounded-full z-10
                                                                                                                                                @if (Str::contains($log->description, 'created')) bg-green-100 text-green-600
                                                                                                                                                @elseif(Str::contains($log->description, 'updated')) bg-blue-100 text-blue-600
                                                                                                                                                @elseif(Str::contains($log->description, 'deleted')) bg-red-100 text-red-600
                                                                                                                                                @else bg-gray-100 text-gray-600 @endif">
                                                @if (Str::contains($log->description, 'created'))
                                                    <i class="fas fa-plus"></i>
                                                @elseif(Str::contains($log->description, 'updated'))
                                                    <i class="fas fa-edit"></i>
                                                @elseif(Str::contains($log->description, 'deleted'))
                                                    <i class="fas fa-trash"></i>
                                                @elseif (Str::contains($log->description, 'logged in'))
                                                    <i class="fas fa-sign-in-alt text-green-500"></i>
                                                @elseif (Str::contains($log->description, 'logged out'))
                                                    <i class="fas fa-sign-out-alt text-yellow-500"></i>
                                                @else
                                                    <i class="fas fa-info-circle"></i>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-1 bg-white dark:bg-gray-700 rounded-lg shadow p-4 ml-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-green-700 dark:text-white">
                                                    {{ $log->log_name ?? 'general' }}
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $log->created_at->format('F d, Y g:i A') }}
                                                </span>
                                            </div>
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
    @endif
</div>