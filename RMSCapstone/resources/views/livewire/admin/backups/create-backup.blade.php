<div class="min-h-[550px] container mx-auto p-6 max-w-full">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-white">Create Database Backup</h2>
        
        <x-button icon="fas fa-arrow-left" href="{{ route('admin.view-backups') }}">
            Back to Backups
        </x-button>
    </div>

    {{-- Display Session Message --}}
    @if (session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        class="mb-6 px-4 py-2 rounded-lg shadow-lg bg-red-500 text-white">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-x-auto border dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        <div class="p-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-blue-800 dark:text-blue-200">Important Information</h3>
                        <p class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            Creating a database backup may take several minutes depending on your database size. 
                            The backup process will lock certain tables during execution. It's recommended to 
                            perform backups during off-peak hours.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Backup Type
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Full Database Backup (MySQL Dump)</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Estimated Size
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                @if(is_numeric($dbSize))
                                    Approximately {{ number_format($dbSize) }} KB
                                @else
                                    Size: {{ $dbSize }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button icon="fas fa-arrow-left" href="{{ route('admin.view-backups') }}">
                        Cancel
                    </x-button>
                    
                    <x-button icon="fas fa-download" type="button" 
                        wire:click="createBackup" 
                        wire:loading.attr="disabled"
                        class="bg-blue-600 hover:bg-blue-700"
                        :disabled="$isCreating">
                        <span wire:loading.remove>Create Backup Now</span>
                        <span wire:loading>Creating Backup...</span>
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>