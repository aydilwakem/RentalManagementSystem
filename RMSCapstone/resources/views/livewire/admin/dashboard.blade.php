<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-black leading-tight dark:text-white">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        <!-- Greeting -->
        <div class="sm:col-span-3">
            <h1 class="text-xl font-semibold">
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
            <p class="text-2xl font-bold text-white"> 4 </p>
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
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    eventContent: function(arg) {
                        let title = arg.event.title;
                        let room = arg.event.extendedProps.room || '';
                        let time = arg.event.extendedProps.time || '';
                        let pax = arg.event.extendedProps.pax || '';
                        let status = arg.event.extendedProps.transaction_status || '';

                        // Reservation Details
                        let firstLine = `<div class="text-sm font-semibold">${title}`;
                        if (room) firstLine += ` | Room: ${room}`;
                        if (pax) firstLine += ` | ${pax} pax`;
                        firstLine += '</div>';

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
        .status-pending {
            background-color: #facc15 !important;
            /* yellow */
            color: #000 !important;
        }

        .status-confirmed {
            background-color: #4ade80 !important;
            /* green */
            color: #fff !important;
        }

        .status-cancelled {
            background-color: #f87171 !important;
            /* red */
            color: #fff !important;
        }

        .status-default {
            background-color: #a5b4fc !important;
            /* indigo */
            color: #fff !important;
        }
    </style> --}}
</div>
