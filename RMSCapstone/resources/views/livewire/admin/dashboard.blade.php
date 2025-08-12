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
            class="bg-yellow-50  border-green-800 border-2 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center hover:scale-105">
            <i class="fas fa-calendar-check text-green-800 text-4xl"></i>
            <h2 class="text-green-800 font-semibold ">New Reservations</h2>
            <p class="text-2xl font-bold text-green-800">{{ $newReservations }}</p>
        </div>


        <!-- Rooms Card -->
        <div
            class="bg-secondary-800 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-bed text-white text-4xl"></i>
            <h2 class="text-white font-semibold">Upcoming Events</h2>
            <p class="text-2xl font-bold text-white"> {{ $upcomingEvents }}</p>
        </div>

        <!-- Maintenance Card -->
        <div
            class="bg-secondary-800 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-tools text-white text-4xl"></i>
            <h2 class="text-white font-semibold">Pending Maintenances</h2>
            <p class="text-2xl font-bold text-white">{{ $pendingMaintenances }}</p>
        </div>
    </div>
    <div class="mb-4 flex items-center space-x-2">
        <label for="reservationFilter" class="text-sm font-medium text-gray-900 dark:text-white">View:</label>
        <select id="reservationFilter"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block p-2.5 w-40
        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            <option value="all">All</option>
            <option value="2">Room Reservation</option>
            <option value="3">Event Bookings</option>
        </select>
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
                    '3': 'Event Bookings'
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
                        if (info.event.extendedProps.transaction_status === 'done') {
                            info.el.classList.add('status-done');
                        } else {
                            info.el.classList.add('status-active');
                        }
                    },
                    eventContent: function(arg) {
                        let title = arg.event.title;
                        let room = arg.event.extendedProps.room || '';
                        let pax = arg.event.extendedProps.pax || '';
                        let status = arg.event.extendedProps.transaction_status || '';

                        let firstLine = '<div class="text-sm">';
                        if (status === 'done') {
                            firstLine += 'Reservation Completed | Click to View Details';
                        } else {
                            firstLine += title;
                            if (room) firstLine += ` | Room: ${room}`;
                            if (pax) firstLine += ` | ${pax} pax`;
                        }
                        firstLine += '</div>';

                        return {
                            html: firstLine
                        };
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

    {{-- <style>
        .status-done {
            background-color: #9ca3af !important;
            /* gray-400 */
            border-color: #6b7280 !important;
            /* gray-500 */
            color: #fff !important;
        }

        .status-active {
            background-color: #4ade80 !important;
            /* green-400 */
            border-color: #22c55e !important;
            /* green-500 */
            color: #fff !important;
        }
    </style> --}}
</div>
