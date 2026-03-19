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
                <div
                    class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div
                    class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards with enhanced styling -->
            <div
                class="bg-white rounded-xl shadow-lg p-5 mb-6 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-900/20 p-4 rounded-xl border border-green-200 dark:border-green-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs text-green-600 dark:text-green-400 font-medium uppercase tracking-wider">
                                    Available Today</div>
                                <div class="text-2xl font-bold text-green-700 dark:text-green-300 mt-1">
                                    {{ $rooms->count() - $this->getBlockedRoomsCount(now()->format('Y-m-d')) - $this->getReservedRoomsCount(now()->format('Y-m-d')) }}
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 bg-green-200 dark:bg-green-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-300 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-900/20 p-4 rounded-xl border border-yellow-200 dark:border-yellow-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs text-yellow-600 dark:text-yellow-400 font-medium uppercase tracking-wider">
                                    Reserved Today</div>
                                <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mt-1">
                                    {{ $this->getReservedRoomsCount(now()->format('Y-m-d')) }}
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 bg-yellow-200 dark:bg-yellow-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-yellow-600 dark:text-yellow-300 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-900/20 p-4 rounded-xl border border-red-200 dark:border-red-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs text-red-600 dark:text-red-400 font-medium uppercase tracking-wider">
                                    Blocked Today</div>
                                <div class="text-2xl font-bold text-red-700 dark:text-red-300 mt-1">
                                    {{ $this->getBlockedRoomsCount(now()->format('Y-m-d')) }}
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 bg-red-200 dark:bg-red-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-ban text-red-600 dark:text-red-300 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs text-blue-600 dark:text-blue-400 font-medium uppercase tracking-wider">
                                    Total Rooms</div>
                                <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mt-1">
                                    {{ $rooms->count() }}
                                </div>
                            </div>
                            <div
                                class="w-10 h-10 bg-blue-200 dark:bg-blue-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-door-open text-blue-600 dark:text-blue-300 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Navigation with enhanced styling -->
            <div
                class="bg-white rounded-xl shadow-lg p-4 mb-6 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-2 w-full sm:w-auto justify-center sm:justify-start">
                        <button wire:click="previousMonth"
                            class="p-2.5 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-150 hover:scale-105">
                            <i class="fas fa-chevron-left text-gray-600 dark:text-gray-300"></i>
                        </button>
                        <span
                            class="px-6 py-2 font-semibold text-lg dark:text-white min-w-[220px] text-center bg-gray-50 dark:bg-gray-700 rounded-lg">{{ $monthName }}</span>
                        <button wire:click="nextMonth"
                            class="p-2.5 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-150 hover:scale-105">
                            <i class="fas fa-chevron-right text-gray-600 dark:text-gray-300"></i>
                        </button>
                    </div>

                    <button wire:click="goToToday"
                        class="px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-150 shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-calendar-day mr-2"></i>
                        Today
                    </button>
                </div>
            </div>

            <!-- Calendar with enhanced styling -->
            <div
                class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <!-- Week Days Header -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                    @foreach($weekDays as $day)
                        <div
                            class="p-3 text-center font-semibold text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                    @foreach($calendarDays as $day)
                                    @php
                                        $availableCount = $day['totalRooms'] - $day['blockedRoomsCount'] - $day['reservationsCount'];
                                        $hasEvents = $day['blockedRoomsCount'] > 0 || $day['reservationsCount'] > 0;
                                    @endphp
                                    <div
                                        class="min-h-[150px] p-2.5 bg-white dark:bg-gray-800 relative group transition-all duration-150
                                                                                                                                        {{ $day['isCurrentMonth'] ? 'hover:bg-gray-50 dark:hover:bg-gray-700/50' : 'opacity-60' }}
                                                                                                                                        {{ $day['isBlocked'] ? 'bg-gradient-to-br from-red-50/50 to-red-50/30 dark:from-red-900/10 dark:to-red-900/5' : '' }}
                                                                                                                                        {{ $day['reservationsCount'] > 0 && !$day['isBlocked'] ? 'bg-gradient-to-br from-yellow-50/50 to-yellow-50/30 dark:from-yellow-900/5 dark:to-yellow-900/0' : '' }}">
                                        <!-- Day Number - Click for Bulk Actions -->
                                        <div class="flex justify-between items-start">
                                            <span wire:click="selectDate('{{ $day['date'] }}')"
                                                class="inline-flex items-center justify-center w-7 h-7 text-sm font-medium rounded-full cursor-pointer transition-all duration-150
                                                                                                                                                {{ $day['isToday']
                        ? 'bg-gradient-to-br from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 shadow-md'
                        : 'hover:bg-gray-200 dark:hover:bg-gray-700 ' . ($day['isCurrentMonth'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-600') }}
                                                                                                                                                {{ $day['isCurrentMonth'] && !$day['isToday'] ? 'hover:scale-105' : '' }}"
                                                title="Click for bulk actions">
                                                {{ $day['day'] }}
                                            </span>

                                            <!-- Quick Action Menu (3 dots) - For individual room blocking -->
                                            @if($day['isCurrentMonth'])
                                                <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                                                    <button @click.stop="open = !open"
                                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-150 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div x-show="open" x-ref="menu{{ $loop->index }}" @click.away="open = false"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                        class="fixed transform -translate-x-64 mt-2 w-72 bg-white rounded-xl shadow-2xl z-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600"
                                                        style="max-height: 70vh; overflow-y: auto; top: 50%; left: 50%; transform: translate(-50%, -50%);">

                                                        <!-- Header with gradient -->
                                                        <div
                                                            class="sticky top-0 bg-gradient-to-r from-gray-50 to-white dark:from-gray-700 dark:to-gray-800 border-b dark:border-gray-600 p-3 flex justify-between items-center">
                                                            <span
                                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                                                {{ \Carbon\Carbon::parse($day['date'])->format('l, F d, Y') }}
                                                            </span>
                                                            <button @click.stop="open = false"
                                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Block Specific Room Options -->
                                                        <div
                                                            class="px-4 py-2.5 text-xs text-gray-500 dark:text-gray-400 font-medium bg-gray-50 dark:bg-gray-800 border-b dark:border-gray-600">
                                                            <i class="fas fa-bolt mr-1 text-yellow-500"></i>Quick block room:
                                                        </div>

                                                        <!-- Room List with Max Height and Scroll -->
                                                        <div class="max-h-96 overflow-y-auto scrollbar-thin">
                                                            @foreach($rooms as $room)
                                                                @php
                                                                    $isBlocked = in_array($room->id, array_column($day['blockedRooms'] ?? [], 'id'));
                                                                    $hasReservation = $this->roomHasReservation($room, $day['date']);
                                                                @endphp
                                                                <button
                                                                    @click.stop="open = false; $wire.selectRoomForBlocking({{ $room->id }}, '{{ $day['date'] }}')"
                                                                    class="block w-full text-left px-4 py-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150 border-b border-gray-100 dark:border-gray-700 last:border-b-0
                                                                                                                                                                                                                    {{ $isBlocked ? 'text-red-600 dark:text-red-400 bg-red-50/50 dark:bg-red-900/10' : 'text-gray-700 dark:text-gray-300' }}
                                                                                                                                                                                                                    {{ $hasReservation ? 'opacity-60 cursor-not-allowed bg-gray-50 dark:bg-gray-800' : 'cursor-pointer hover:pl-5' }}"
                                                                    {{ $hasReservation ? 'disabled' : '' }} title="{{ $room->name_number }}">
                                                                    <div class="flex items-center justify-between">
                                                                        <span class="flex items-center truncate pr-2">
                                                                            <i
                                                                                class="fas {{ $isBlocked ? 'fa-ban text-red-500' : 'fa-circle text-gray-400' }} mr-3 text-xs flex-shrink-0"></i>
                                                                            <span class="truncate font-medium">{{ $room->name_number }}</span>
                                                                        </span>
                                                                        <span class="flex items-center space-x-1 flex-shrink-0">
                                                                            @if($isBlocked)
                                                                                <span
                                                                                    class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full dark:bg-red-900 dark:text-red-200 font-medium shadow-sm">
                                                                                    Blocked
                                                                                </span>
                                                                            @elseif($hasReservation)
                                                                                <span
                                                                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-200 font-medium shadow-sm">
                                                                                    Reserved
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        </div>

                                                        <!-- Bulk Action with gradient -->
                                                        <div
                                                            class="sticky bottom-0 bg-gradient-to-r from-blue-50 to-white dark:from-gray-700 dark:to-gray-800 border-t dark:border-gray-600">
                                                            <button @click.stop="open = false; $wire.selectDate('{{ $day['date'] }}')"
                                                                class="block w-full text-left px-4 py-3.5 text-sm text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-gray-600 transition-colors duration-150 font-medium">
                                                                <i class="fas fa-layer-group mr-2"></i>
                                                                Bulk Block/Unblock All Rooms
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Status Indicators with enhanced styling -->
                                        <div class="mt-3 space-y-2">
                                            <!-- Availability Bar with tooltip -->
                                            <div class="relative group"
                                                title="Available: {{ $availableCount }} | Reserved: {{ $day['reservationsCount'] }} | Blocked: {{ $day['blockedRoomsCount'] }}">
                                                <div class="flex items-center text-xs gap-2">
                                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                                                        @if($day['totalRooms'] > 0)
                                                            @php
                                                                $blockedPercent = ($day['blockedRoomsCount'] / $day['totalRooms']) * 100;
                                                                $reservedPercent = ($day['reservationsCount'] / $day['totalRooms']) * 100;
                                                                $availablePercent = max(0, 100 - $blockedPercent - $reservedPercent);
                                                            @endphp
                                                            <div class="h-full flex">
                                                                @if($availablePercent > 0)
                                                                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-full transition-all duration-300"
                                                                        style="width: {{ $availablePercent }}%"></div>
                                                                @endif
                                                                @if($reservedPercent > 0)
                                                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-full transition-all duration-300"
                                                                        style="width: {{ $reservedPercent }}%"></div>
                                                                @endif
                                                                @if($blockedPercent > 0)
                                                                    <div class="bg-gradient-to-r from-red-400 to-red-500 h-full transition-all duration-300"
                                                                        style="width: {{ $blockedPercent }}%"></div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="text-gray-500 dark:text-gray-400 font-medium min-w-[45px]">{{ $availableCount }}</span>
                                                </div>
                                            </div>

                                            <!-- Blocked Rooms Summary with enhanced styling -->
                                            @if(!empty($day['blockedRooms']) && count($day['blockedRooms']) > 0)
                                                <div class="text-xs">
                                                    <div
                                                        class="flex items-center text-red-600 dark:text-red-400 font-medium mb-1.5 bg-red-50/50 dark:bg-red-900/10 p-1 rounded">
                                                        <i class="fas fa-ban mr-1.5 text-xs"></i>
                                                        <span>Blocked ({{ count($day['blockedRooms']) }})</span>
                                                    </div>
                                                    <div
                                                        class="space-y-1 max-h-16 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 pl-2">
                                                        @foreach($day['blockedRooms'] as $blockedRoom)
                                                            <div
                                                                class="flex items-center justify-between group/room px-1 py-0.5 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                                <span
                                                                    class="text-gray-600 dark:text-gray-400 text-xs truncate flex items-center"
                                                                    title="{{ $blockedRoom['name'] }}">
                                                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                                                                    {{ $blockedRoom['name'] }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Reservations Count with enhanced styling -->
                                            @if($day['reservationsCount'] > 0)
                                                <div
                                                    class="flex items-center text-xs text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 p-1.5 rounded">
                                                    <i class="fas fa-calendar-check mr-1.5"></i>
                                                    <span class="font-medium">{{ $day['reservationsCount'] }}</span>
                                                    <span class="ml-1">{{ Str::plural('reservation', $day['reservationsCount']) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Legend with enhanced styling -->
            <div
                class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-green-400 to-green-500 rounded-full mr-2 shadow-sm">
                        </div>
                        <span class="text-gray-600 dark:text-gray-400">Available</span>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="w-4 h-4 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full mr-2 shadow-sm">
                        </div>
                        <span class="text-gray-600 dark:text-gray-400">Reserved</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-red-400 to-red-500 rounded-full mr-2 shadow-sm">
                        </div>
                        <span class="text-gray-600 dark:text-gray-400">Blocked</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add this to your styles section or CSS file for scrollbar styling -->
    <style>
        /* Custom scrollbar for webkit browsers */
        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4B5563;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #6B7280;
        }
    </style>

    <!-- Bulk Block/Unblock Modal with improved horizontal layout -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto dark:bg-gray-800 transform transition-all animate-modalFadeIn border border-gray-100 dark:border-gray-700">

                <!-- Sticky Header -->
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b dark:border-gray-700 p-6 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <div
                                class="w-8 h-8 {{ $isBlocked ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-lg flex items-center justify-center mr-3">
                                <i class="fas {{ $isBlocked ? 'fa-unlock text-green-600' : 'fa-ban text-red-600' }}"></i>
                            </div>
                            {{ $isBlocked ? 'Unblock Rooms' : 'Block Rooms' }}
                        </h3>
                        <button wire:click="$set('showModal', false)"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    @php
                        $reservationsCount = $selectedDate ? $this->getReservedRoomsCount($selectedDate) : 0;
                        $blockedRoomsList = $selectedDate ? $this->getBlockedRoomsList($selectedDate) : [];
                        $isBlocked = $selectedDate ? $this->isDateBlocked($selectedDate) : false;
                    @endphp

                    <!-- Date Info - Horizontal Layout -->
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                                Selected Date:
                            </span>
                            <span class="font-semibold text-lg text-blue-700 dark:text-blue-300">
                                {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Mode Toggle - Compact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode:</label>
                        <div class="flex space-x-2 bg-gray-100 dark:bg-gray-700 p-1 rounded-lg w-fit">
                            <button wire:click="$set('bulkMode', true)"
                                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-150 whitespace-nowrap
                                    {{ $bulkMode ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                <i class="fas fa-layer-group mr-2"></i>All Rooms
                            </button>
                            <button wire:click="$set('bulkMode', false)"
                                class="px-4 py-2 text-sm font-medium rounded-md transition-all duration-150 whitespace-nowrap
                                    {{ !$bulkMode ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                <i class="fas fa-check-double mr-2"></i>Select Rooms
                            </button>
                        </div>
                    </div>

                    <!-- Bulk Mode Info - Condensed -->
                    @if($bulkMode)
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <p class="text-sm text-blue-700 dark:text-blue-400">
                                    This will <strong>{{ $isBlocked ? 'unblock' : 'block' }} ALL {{ $rooms->count() }}
                                        rooms</strong> on this date.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Room Selection with Grid Layout -->
                    @if(!$bulkMode)
                        <div>
                            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Select Rooms to {{ $isBlocked ? 'Unblock' : 'Block' }}:
                                </label>
                                <div class="flex items-center space-x-3">
                                    <button type="button" wire:click="clearSelection"
                                        class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                        Clear
                                    </button>
                                    <span class="text-xs text-gray-300 dark:text-gray-600">|</span>
                                    <button type="button" wire:click="toggleAllRooms"
                                        class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors">
                                        {{ $allRoomsSelected ? 'Deselect All' : 'Select All' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Grid Layout for Rooms -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-60 overflow-y-auto border dark:border-gray-600 rounded-xl p-2 scrollbar-thin">
                                @foreach($rooms as $room)
                                    @php
                                        $roomBlocked = in_array($room->id, array_column($blockedRoomsList, 'id'));
                                        $roomReserved = $this->roomHasReservation($room, $selectedDate);
                                        if ($isBlocked && !$roomBlocked)
                                            continue;
                                        if (!$isBlocked && $roomBlocked)
                                            continue;
                                        $isChecked = in_array($room->id, $selectedRooms);
                                    @endphp
                                    <label class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-150 rounded-lg
                                                            {{ $roomReserved ? 'opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-800' : '' }}
                                                            {{ $isChecked ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                        <input type="checkbox" wire:model.live="selectedRooms" value="{{ $room->id }}" {{ $roomReserved ? 'disabled' : '' }}
                                            class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 flex-shrink-0">
                                        <div class="ml-2 flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $room->name_number }}</span>
                                                <div class="flex space-x-1 ml-2 flex-shrink-0">
                                                    @if($roomBlocked)
                                                        <span
                                                            class="text-xs bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-200">Blocked</span>
                                                    @endif
                                                    @if($roomReserved)
                                                        <span
                                                            class="text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-200">Reserved</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Selection Summary - Compact -->
                            <div class="mt-2 flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">
                                    <span
                                        class="font-semibold text-blue-600 dark:text-blue-400">{{ count($selectedRooms) }}</span>
                                    selected
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $isBlocked ? count($blockedRoomsList) : $rooms->count() - $reservationsCount }} available
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Reservation Warning - Compact -->
                    @if($reservationsCount > 0)
                        <div
                            class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-3">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5"></i>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                    {{ $reservationsCount }} room(s) have reservations on this date.
                                    @if($isBlocked) Unblocking may affect these reservations.
                                    @else Rooms with reservations cannot be blocked. @endif
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Reason Input - Compact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-pencil-alt mr-2 text-gray-400"></i>Reason (Optional)
                        </label>
                        <textarea wire:model="blockReason" rows="2"
                            class="w-full border dark:border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-150"
                            placeholder="e.g., Maintenance, Holiday, Private Event..."></textarea>
                    </div>
                </div>

                <!-- Sticky Footer with Actions -->
                <div
                    class="sticky bottom-0 bg-gray-50 dark:bg-gray-800 border-t dark:border-gray-700 p-4 flex justify-end space-x-3">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-all duration-150 font-medium">
                        Cancel
                    </button>

                    @if($isBlocked)
                        <button wire:click="unblockRooms" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 disabled:opacity-50 transition-all duration-150 shadow-md hover:shadow-lg flex items-center">
                            <span wire:loading.remove wire:target="unblockRooms">
                                <i class="fas fa-unlock mr-2"></i>Unblock @if(!$bulkMode)({{ count($selectedRooms) }})@endif
                            </span>
                            <span wire:loading wire:target="unblockRooms" class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    @else
                        <button wire:click="blockRooms" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 disabled:opacity-50 transition-all duration-150 shadow-md hover:shadow-lg flex items-center">
                            <span wire:loading.remove wire:target="blockRooms">
                                <i class="fas fa-ban mr-2"></i>Block @if(!$bulkMode)({{ count($selectedRooms) }})@endif
                            </span>
                            <span wire:loading wire:target="blockRooms" class="flex items-center">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Individual Room Blocking Modal - Unlimited Dates -->
    @if($showRoomSelectionModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 dark:bg-gray-800 transform transition-all animate-modalFadeIn border border-gray-100 dark:border-gray-700">

                <!-- Header with gradient -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div
                            class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-plus text-purple-600"></i>
                        </div>
                        Block Room for Multiple Dates
                    </h3>
                    <button wire:click="$set('showRoomSelectionModal', false)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                @php $room = $rooms->find($selectedRoomId); @endphp

                @if($room)
                    <!-- Room Info Card with gradient -->
                    <div class="mb-6">
                        <div
                            class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 border border-purple-200 dark:border-purple-800 rounded-xl p-4">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-purple-200 dark:bg-purple-800 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-door-open text-purple-600 dark:text-purple-300 text-xl"></i>
                                </div>
                                <div>
                                    <span
                                        class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wider font-medium">Room</span>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $room->name_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Original Date Card -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Original Date (will be blocked):
                        </label>
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-green-600 dark:text-green-400 mr-3 text-lg"></i>
                                    <span class="font-semibold text-green-700 dark:text-green-300">
                                        {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}
                                    </span>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 text-xs rounded-full font-medium shadow-sm">
                                    Primary
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Dates - Unlimited! -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">

                                Additional dates (optional):
                            </label>
                            <button type="button" wire:click="addDateField"
                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium flex items-center bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-full">
                                <i class="fas fa-plus mr-1"></i> Add another date
                            </button>
                        </div>

                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1 scrollbar-thin">
                            @foreach($roomBlockDates as $index => $date)
                                <div class="relative group" wire:key="date-field-{{ $index }}">
                                    <input type="date" wire:model="roomBlockDates.{{ $index }}"
                                        class="w-full border dark:border-gray-600 rounded-lg p-3 pl-10 pr-10 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-150">
                                    <i
                                        class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                    @if(count($roomBlockDates) > 0)
                                        <button type="button" wire:click="removeDateField({{ $index }})"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                            <!-- Show placeholder when no additional dates -->
                            @if(count($roomBlockDates) == 0)
                                <div
                                    class="text-center py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                    <i class="fas fa-calendar-plus text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Click "Add another date" to add more dates
                                    </p>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                            Add as many additional dates as you need. Click the X to remove.
                        </p>
                    </div>

                    <!-- Summary Card -->
                    @php
                        $additionalDatesCount = count(array_filter($roomBlockDates, function ($date) {
                            return !empty($date);
                        }));
                        $totalDates = 1 + $additionalDatesCount;
                    @endphp

                    @if($additionalDatesCount > 0)
                        <div
                            class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-layer-group text-purple-500 text-lg mt-0.5 mr-3"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-300">Summary</p>
                                    <p class="text-sm text-purple-700 dark:text-purple-400 mt-1">
                                        Blocking <strong>{{ $room->name_number }}</strong> for <strong>{{ $totalDates }}
                                            {{ Str::plural('date', $totalDates) }}</strong>
                                    </p>
                                    <p class="text-xs text-purple-500 dark:text-purple-500 mt-1 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        1 primary + {{ $additionalDatesCount }} additional
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Reason Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                            <i class="fas fa-pencil-alt mr-2 text-gray-400"></i>
                            Reason (Optional)
                        </label>
                        <textarea wire:model="blockReason" rows="3"
                            class="w-full border dark:border-gray-600 rounded-xl p-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-150"
                            placeholder="e.g., Maintenance, Holiday, Private Event..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showRoomSelectionModal', false)"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-all duration-150 font-medium">
                            Cancel
                        </button>

                        <button wire:click="blockRoomForDates" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 disabled:opacity-50 transition-all duration-150 shadow-lg hover:shadow-xl flex items-center">
                            <span wire:loading.remove wire:target="blockRoomForDates">
                                <i class="fas fa-ban mr-2"></i>
                                Block {{ $totalDates }} {{ Str::plural('Date', $totalDates) }}
                            </span>
                            <span wire:loading wire:target="blockRoomForDates">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Processing...
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>