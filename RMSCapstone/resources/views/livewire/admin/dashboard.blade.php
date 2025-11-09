<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-black leading-tight dark:text-white py-1">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <!-- Greeting -->
        <div class="sm:col-span-3">
            <h1 class="text-xl font-semibold dark:text-white">
                Hello, {{ $first_name }} {{ $last_name }}!
            </h1>
        </div>


        <!-- Reservations Card -->
        <div
            class="bg-yellow-50 border-yellow-100 border-2 rounded-xl shadow p-6 flex items-center justify-between relative hover:shadow-md transition">
            <!-- Shortcut -->
            <div class="absolute top-2 right-2">
                <button
                    class="dropdownButton text-gray-500 bg-white border hover:bg-gray-300 rounded-full w-7 h-7 flex items-center justify-center text-md focus:outline-none">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>

                <!-- Dropdown -->
                <div
                    class="dropdownMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg py-1 z-50">
                    <a href="{{ route('admin.create-reservation') }}" wire:navigate
                        class="pl-3 block py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Create Reservation
                    </a>
                    <a href="{{ route('admin.reservations-list') }}" wire:navigate
                        class="pl-3 block py-2 text-sm text-gray-700 hover:bg-gray-100">
                        View All Reservations
                    </a>
                </div>
            </div>

            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-full bg-yellow-200 flex items-center justify-center">
                    <i class="fas fa-bed text-yellow-700 text-4xl"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col items-center text-center flex-1">
                <h2 class="text-gray-800 font-semibold">New Reservations</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $newReservations }}</p>
                <p class="text-xs text-gray-600 mt-2">
                    For the month of {{ now()->format('F Y') }}
                </p>
            </div>
        </div>




        <!-- Events Card -->
        <div
            class="bg-yellow-50 border-yellow-100 border-2 rounded-xl shadow p-6 flex items-center justify-between relative hover:shadow-md transition">
            <div class="absolute top-2 right-2">
                <button
                    class="dropdownButton text-gray-500 bg-white border hover:bg-gray-300 rounded-full w-7 h-7 flex items-center justify-center text-md focus:outline-none">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>

                <!-- Dropdown -->
                <div
                    class="dropdownMenu hidden absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg py-1 z-50">
                    <a href="{{ route('admin.create-event') }}" wire:navigate
                        class="pl-3 block py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Create Event
                    </a>
                    <a href="{{ route('admin.events') }}" wire:navigate
                        class="pl-3 block py-2 text-sm text-gray-700 hover:bg-gray-100">
                        View All Events
                    </a>
                </div>
            </div>

            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-full bg-yellow-200 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-yellow-700 text-4xl"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col items-center text-center flex-1">
                <h2 class="text-gray-800 font-semibold">Upcoming Events</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $upcomingEvents }}</p>
                <p class="text-xs text-gray-600 mt-2">
                    For the month of {{ now()->format('F Y') }}
                </p>
            </div>
        </div>

        <!-- Maintenances Card -->
        <div
            class="bg-yellow-50 border-yellow-100 border-2 rounded-xl shadow p-6 flex items-center justify-between relative hover:shadow-md transition">
            <div class="absolute top-2 right-2">
                <button
                    class="dropdownButton text-gray-500 bg-white border hover:bg-gray-300 rounded-full w-7 h-7 flex items-center justify-center text-md focus:outline-none">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>

                <!-- Dropdown -->
                <div
                    class="dropdownMenu hidden absolute right-0 mt-2 w-44 bg-white border rounded-lg shadow-lg py-1 z-50">
                    <a href="{{ route('admin.create-maintenance') }}" wire:navigate
                        class="pl-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Create Maintenance
                    </a>
                    <a href="{{ route('admin.maintenances') }}" wire:navigate
                        class="pl-3 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        View All Maintenances
                    </a>
                </div>
            </div>

            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 rounded-full bg-yellow-200 flex items-center justify-center">
                    <i class="fas fa-tools text-yellow-700 text-4xl"></i>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col items-center text-center flex-1">
                <h2 class="text-gray-800 font-semibold">Pending Maintenances</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $pendingMaintenances }}</p>
                <p class="text-xs text-gray-600 mt-2">
                    For the month of {{ now()->format('F Y') }}
                </p>

            </div>

        </div>

        <script>
            // Select all buttons
            const buttons = document.querySelectorAll('.dropdownButton');

            buttons.forEach(button => {
                const menu = button.parentElement.querySelector('.dropdownMenu');

                // Toggle on click
                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close other open dropdowns
                    document.querySelectorAll('.dropdownMenu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });

                    menu.classList.toggle('hidden');
                });
            });

            // Close when clicking outside
            window.addEventListener('click', function() {
                document.querySelectorAll('.dropdownMenu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });
        </script>

    </div>

    <!-- Calendar Sort -->
    <div class="flex justify-between">
        <div class="mb-4 flex items-center space-x-2">
            <label for="reservationFilter" class="text-sm font-medium text-gray-900 dark:text-white">View:</label>
            <select id="reservationFilter"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block p-2.5 w-40
            dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white focus:ring-green-600 focus:border-green-600 focus:outline-none">
                <option value="all">All</option>
                <option value="2">Room Reservation</option>
                <option value="3">Event Bookings</option>
                <option value="4">Day Tour Bookings</option>
            </select>
        </div>
        <div class="flex items-center space-x-4 mb-3">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-4 h-4 rounded bg-green-300"></span>
                <span class="text-sm dark:text-white">Room Reservations</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-block w-4 h-4 rounded bg-cyan-300"></span>
                <span class="text-sm dark:text-white">Event Bookings</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-block w-4 h-4 rounded bg-yellow-200"></span>
                <span class="text-sm dark:text-white">Day Tour Bookings</span>
            </div>
        </div>
    </div>


    <div id='calendar'></div>

    <div class="mt-2">
        <span id="currentViewLabel" class="font-semibold text-lg ml-1"></span>
    </div>

    @script
        <script type="text/javascript">
            document.addEventListener('livewire:initialized', () => {
                var calendarEl = document.getElementById('calendar');
                var events = @json($events);

                // Initial filtered events
                let filteredEvents = [...events];

                // Map for label names
                const labelMap = {
                    'all': 'All Transactions',
                    '2': 'Room Reservations',
                    '3': 'Event Bookings',
                    '4': 'Day Tour Bookings'
                };

                // Default label
                let currentLabel = labelMap['all'];

                // Calendar layout
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    events: filteredEvents,
                    headerToolbar: {
                        left: 'customLabel',
                        center: 'title',
                        right: 'prev,next'
                    },
                    customButtons: {
                        customLabel: {
                            text: currentLabel,
                            click: null // no action needed
                        }
                    },
                    eventDidMount: function(info) {
                        // Status Styling
                        if (info.event.extendedProps.transaction_status === 'done') {
                            info.el.classList.add('status-done');
                        } else {
                            info.el.classList.add('status-active');
                        }

                        // Reservation Type Styling
                        if (info.event.extendedProps.type_id == 3) {
                            info.el.classList.add('event-booking');
                        } else if (info.event.extendedProps.type_id == 2) {
                            info.el.classList.add('room-reservation');
                        } else if (info.event.extendedProps.type_id == 4) {
                            info.el.classList.add('daytour-booking');
                        }
                    },
                    eventContent: function(arg) {
                        let title = arg.event.title || '';
                        let room = arg.event.extendedProps.room || '';
                        let pax = arg.event.extendedProps.pax || '';
                        let status = arg.event.extendedProps.transaction_status || '';

                        let firstLine = '<div class="text-sm truncate overflow-hidden whitespace-nowrap">';
                        if (status === 'done') {
                            firstLine += 'Reservation Completed | Click to View Details';
                        } else {
                            if (room) firstLine += ` ${room}`;
                            if (pax) firstLine += ` | ${pax} pax`;
                            if (title) {
                                if (room || pax) firstLine += ' | ';
                                firstLine += title;
                            }
                        }
                        firstLine += '</div>';

                        return { html: firstLine };
                    },
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        if (info.event.url) {
                            Livewire.navigate(info.event.url);
                        }
                    }
                });

                calendar.render();

                // Handle dropdown change
                document.getElementById('reservationFilter').addEventListener('change', function() {
                    let selectedType = this.value;

                    // Filter events
                    let filtered = selectedType === 'all' ?
                        events :
                        events.filter(e => String(e.type_id) === selectedType);

                    calendar.removeAllEvents();
                    calendar.addEventSource(filtered);

                    // Update custom button label
                    const newLabel = labelMap[selectedType] || 'Reservations';
                    calendar.setOption('customButtons', {
                        customLabel: {
                            text: newLabel,
                            click: null
                        }
                    });

                    // Force re-render of header
                    calendar.setOption('headerToolbar', {
                        left: 'customLabel',
                        center: 'title',
                        right: 'prev,next'
                    });
                });
            });
        </script>
    @endscript

</div>
