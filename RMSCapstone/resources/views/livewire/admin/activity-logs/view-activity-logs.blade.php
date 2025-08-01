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
            <!-- Table -->
            <div
                class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                <!-- Header-->
                <div class="flex items-center justify-between p-4">
                    {{-- Search Tab --}}
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2
                                                                                                                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Search" required="">
                        </div>
                    </div>


                </div>

                {{-- Table Body --}}
                <div wire:loading wire:target="search, statusFilter, logNameFilter, roleFilter, dateFilter"
                    class="w-full flex items-center justify-center min-h-[80px] relative mt-20">
                    <div class="flex flex-col items-center justify-center text-center">
                        <!-- Spinner -->
                        <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.33 0 0 5.33 0 12s5.33 12 12 12v-4a8 8 0 01-8-8z">
                            </path>
                        </svg>
                        <span class="text-blue-600 text-sm font-medium">
                            Loading filtered activity logs...
                        </span>
                    </div>
                </div>

                <table class="w-full text-left">

                    <thead wire:loading.remove wire:target="search"
                        class="text-sm text-gray-700 bg-gray-200 dark:bg-gray-800 dark:text-white dark:border-t dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-3">ID</th>
                            <th scope="col" class="px-4 py-3">Log Name</th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            <th scope="col" class="px-4 py-3">Cause</th>
                            <th scope="col" class="px-4 py-3">Timestamps</th>
                        </tr>
                    </thead>

                    <tbody wire:loading.remove wire:target="search, logNameFilter, roleFilter, dateFilter"
                        class="text-left dark:bg-gray-700">
                        @forelse ($logs as $log)
                            <tr
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-700 odd:dark:bg-gray-700 even:dark:bg-gray-800">

                                <!-- Log ID -->
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $log->id }}
                                </td>


                                <!-- Log Name -->
                                <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-clipboard-list text-blue-600"></i>
                                    <span class="capitalize">{{ $log->log_name ?? 'General' }}</span>
                                </td>

                                <!-- Description -->
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $log->description }}
                                </td>

                                <!-- Causer -->
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-user text-green-600"></i>
                                    <span>{{ $log->causer->name ?? 'System' }}</span>
                                </td>

                                <!-- Date -->
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    <i class="fas fa-clock text-yellow-500 mr-1"></i>
                                    {{ $log->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500 dark:text-gray-200">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>


                </table>
                <div class="py-6 px-4 bg-white shadow-sm dark:bg-gray-800 dark:text-white rounded-lg">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-3">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Per Page</label>
                            <select wire:model.live="perPage"
                                class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg p-2 w-24
                                                                                                                                                            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
    @endif
    </div>
</div>