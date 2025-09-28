<div class="min-h-screen container mx-auto p-6 max-w-full">
    <div class="bg-white rounded-lg shadow-md border dark:bg-gray-800 dark:border-gray-700">
        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Rebook Reservation - {{ $transaction->transaction_number ?? 'N/A' }}
                </h2>
                <x-button href="{{ route('admin.reservations-list') }}" icon="fas fa-arrow-left">
                    Back to Reservations
                </x-button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Reservation Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Current Reservation Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Guest:</span> 
                        {{ $transaction->transactionUser->first_name ?? 'N/A' }} 
                        {{ $transaction->transactionUser->last_name ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Room:</span> 
                        {{ $room->name_number ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Status:</span> 
                        <span class="capitalize">{{ str_replace('_', ' ', $transaction->transaction_status) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Current Check-in:</span> 
                        {{ $transaction->start_datetime ? \Carbon\Carbon::parse($transaction->start_datetime)->format('M j, Y h:i A') : 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Current Check-out:</span> 
                        {{ $transaction->end_datetime ? \Carbon\Carbon::parse($transaction->end_datetime)->format('M j, Y h:i A') : 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Stay Duration:</span> 
                        {{ $stay_duration }} night(s)
                    </div>
                    <div>
                        <span class="font-medium">Guests:</span> 
                        {{ $transaction->total_adults ?? 0 }} Adults, 
                        {{ $transaction->total_kids ?? 0 }} Kids
                    </div>
                </div>
            </div>

            <!-- Rebooking Form -->
            <form wire:submit.prevent="rebook">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Select New Dates</h3>
                        
                        <!-- Check-in Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                New Check-in Date *
                            </label>
                            <input type="date" 
                                   wire:model.blur="check_in_date"
                                   min="{{ $min_date }}"
                                   max="{{ $max_date }}"
                                   class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('check_in_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Auto-calculated Check-out Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                New Check-out Date (Auto-calculated)
                            </label>
                            <input type="text" 
                                   value="{{ $new_check_out_date ? \Carbon\Carbon::parse($new_check_out_date)->format('M j, Y') : 'N/A' }}"
                                   readonly
                                   class="w-full p-2 border border-gray-300 rounded bg-gray-100 dark:bg-gray-600 dark:border-gray-600 dark:text-white cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Based on original {{ $stay_duration }} night stay</p>
                        </div>

                        <!-- Stay Duration -->
                        <div class="p-3 bg-blue-50 rounded dark:bg-blue-900/20">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Stay Duration:</span> 
                                <span class="font-semibold">{{ $stay_duration }} night(s)</span>
                            </div>
                            <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                                Preserved from original reservation
                            </p>
                        </div>

                        <!-- Availability Check -->
                        @if($check_in_date && !$errors->has('check_in_date'))
                            <div class="p-3 bg-green-50 rounded dark:bg-green-900/20">
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Room is available for the selected dates
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Changes Summary -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Changes Summary</h3>
                        
                        <div class="p-3 bg-yellow-50 rounded dark:bg-yellow-900/20">
                            <h4 class="font-medium mb-2">Reservation Changes</h4>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                                <li>• Check-in date will be updated</li>
                                <li>• Check-out date will be auto-adjusted</li>
                                <li>• Stay duration remains {{ $stay_duration }} nights</li>
                                <li>• Guest information remains unchanged</li>
                                <li>• Room selection remains unchanged</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button type="button" 
                                       href="{{ route('admin.reservations-list') }}">
                        Cancel
                    </x-secondary-button>
                    <x-button type="submit" 
                             icon="fas fa-calendar-check"
                             :disabled="!$check_in_date || $stay_duration === 0 || $errors->has('check_in_date')">
                        Rebook Reservation
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>