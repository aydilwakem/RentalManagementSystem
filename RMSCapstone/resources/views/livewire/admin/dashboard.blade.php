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
            class="bg-secondary-800 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-calendar-check text-white text-4xl"></i>
            <h2 class="text-white font-semibold">New Reservations</h2>
            <p class="text-2xl font-bold text-white">{{ $newReservations }}</p>
        </div>


        <!-- Rooms Card -->
        <div
            class="bg-secondary-800 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-bed text-white text-4xl"></i>
            <h2 class="text-white font-semibold">Rooms Available</h2>
            <p class="text-2xl font-bold text-white"> {{ $availableRooms }}</p>
        </div>

        <!-- Maintenance Card -->
        <div
            class="bg-secondary-800 rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-tools text-white text-4xl"></i>
            <h2 class="text-white font-semibold">Pending Maintenances</h2>
            <p class="text-2xl font-bold text-white">{{ $pendingMaintenances }}</p>
        </div>
    </div>

    <div id='calendar'></div>

    @script
        <script type="text/javascript">
            document.addEventListener('livewire:initialized', () => {
                var calendarEl = document.getElementById('calendar');
                var events = @json($events);

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    events: events,
                    headerToolbar: {
                        left: 'today',
                        center: 'title',
                        right: 'prev,next'
                        //right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    eventDidMount: function(info) {
                        // Add status-based class to event
                        if (info.event.extendedProps.transaction_status === 'done') {
                            info.el.classList.add('status-done');
                        } else {
                            info.el.classList.add('status-active');
                        }
                    },
                    eventContent: function(arg) {
                        let title = arg.event.title;
                        let room = arg.event.extendedProps.room || '';
                        let time = arg.event.extendedProps.time || '';
                        let pax = arg.event.extendedProps.pax || '';
                        let status = arg.event.extendedProps.transaction_status || '';

                        // Reservation Details
                        let firstLine = '<div class="text-sm">';
                        if (status === 'done') {
                            firstLine += 'Reservation Completed | Click to View Details';  // For completed reservations
                        } else {
                            firstLine += title;  // Guest Name
                            if (room) firstLine += ` | Room: ${room}`; // Room Name
                            if (pax) firstLine += ` | ${pax} pax`; // Guest Pax
                            firstLine += '</div>';
                        }

                        // Time
                        // let secondLine = time ? `<div class="text-xs text-gray-600">${time}</div>` : '';

                        let html = firstLine;

                        return {
                            html: html
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
