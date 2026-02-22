<div>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white mb-1">
            {{ __('Room Blocking Calendar') }}
        </h2>

    </x-slot>

    <!-- Body Container -->
    <div class="py-3">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if (session()->has('message'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                    {{ session('message') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif


            <!-- Simple Date Navigation -->
            <div class="bg-white rounded-lg shadow p-4 mb-6 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <!-- Month Navigation -->
                    <div class="flex items-center space-x-2">
                        <button wire:click="previousMonth" class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="px-4 py-2 font-semibold text-lg dark:text-white">{{ $monthName }}</span>
                        <button wire:click="nextMonth" class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Today Button -->
                    <button wire:click="goToToday" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Today
                    </button>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
                <!-- Week Days Header -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                    @foreach($weekDays as $day)
                        <div class="p-3 text-center font-semibold text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                    @foreach($calendarDays as $day)
                        <div 
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="min-h-[120px] p-2 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 relative {{ $day['isBlocked'] ? 'bg-red-50 dark:bg-red-900/20' : '' }}"
                        >
                            <div class="flex justify-between items-start">
                                <span class="text-sm {{ $day['isCurrentMonth'] ? 'font-semibold dark:text-white' : 'text-gray-400 dark:text-gray-600' }} 
                                    {{ $day['isToday'] ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : '' }}">
                                    {{ $day['day'] }}
                                </span>
                            </div>
                            
                            <!-- Status Indicators -->
                            <div class="mt-2 space-y-1">
                                @if($day['isBlocked'])
                                    <div class="flex items-center text-xs">
                                        <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            Blocked
                                        </span>
                                    </div>
                                @endif
                                
                                @if($day['reservationsCount'] > 0)
                                    <div class="flex items-center text-xs">
                                        <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></span>
                                        <span class="text-yellow-600 dark:text-yellow-400">
                                            {{ $day['reservationsCount'] }} {{ Str::plural('reservation', $day['reservationsCount']) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Blocked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Has Reservations</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-600 text-white text-xs flex items-center justify-center rounded-full mr-2">12</div>
                    <span class="text-gray-600 dark:text-gray-400">Today</span>
                </div>
            </div>
        
        </div>
    </div>


    <!-- Block/Unblock Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 dark:bg-gray-800">
                @php
                    $reservationsCount = $selectedDate ? $this->getReservedRoomsCount($selectedDate) : 0;
                    $isBlocked = $selectedDate ? $this->isDateBlocked($selectedDate) : false;
                @endphp
                
                <h3 class="text-lg font-semibold text-gray-900 mb-4 dark:text-white">
                    {{ $isBlocked ? 'Unblock Date' : 'Block Date' }}
                </h3>
                
                <p class="text-gray-600 mb-2 dark:text-gray-300">
                    Selected Date: <span class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span>
                </p>
                
                @if($reservationsCount > 0)
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900 dark:border-yellow-700">
                        <p class="text-yellow-700 dark:text-yellow-300">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            There {{ $reservationsCount == 1 ? 'is' : 'are' }} <strong>{{ $reservationsCount }}</strong> 
                            {{ Str::plural('reservation', $reservationsCount) }} on this date.
                        </p>
                    </div>
                @endif
                
                <p class="text-gray-700 mb-4 dark:text-gray-300 text-center text-lg">
                    Are you sure you want to <span class="font-bold {{ $isBlocked ? 'text-green-600' : 'text-red-600' }}">
                        {{ $isBlocked ? 'unblock' : 'block' }}</span> this date?
                </p>
                
                <!-- Reason Input (Optional) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        Reason (Optional)
                    </label>
                    <textarea 
                        wire:model="blockReason"
                        rows="2"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="e.g., Maintenance, Holiday, Private Event..."
                    ></textarea>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button 
                        wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500"
                    >
                        Cancel
                    </button>
                    
                    @if($isBlocked)
                        <button 
                            wire:click="unblockRooms"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="unblockRooms">
                                <i class="fas fa-unlock mr-2"></i>Yes, Unblock
                            </span>
                            <span wire:loading wire:target="unblockRooms">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    @else
                        <button 
                            wire:click="blockRooms"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="blockRooms">
                                <i class="fas fa-ban mr-2"></i>Yes, Block
                            </span>
                            <span wire:loading wire:target="blockRooms">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>