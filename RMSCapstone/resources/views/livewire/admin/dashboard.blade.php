<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <!-- Greeting -->
        <div class="sm:col-span-3">
            <h1 class="text-xl font-semibold">
                Hello, {{ $first_name }} {{ $last_name }}!
            </h1>
        </div>


        <!-- Reservations Card -->
        <div
            class="bg-highlight rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-calendar-check text-primary text-4xl"></i>
            <h2 class="text-gray-800 font-semibold">New Reservations</h2>
            <p class="text-2xl font-bold text-gray-800">{{ $newReservations }}</p>
        </div>


        <!-- Rooms Card -->
        <div
            class="bg-highlight rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-bed text-primary text-4xl"></i>
            <h2 class="text-gray-800 font-semibold">Rooms Available</h2>
            <p class="text-2xl font-bold text-gray-800"> 4 </p>
        </div>

        <!-- Maintenance Card -->
        <div
            class="bg-highlight rounded-xl shadow p-6 flex flex-col items-center justify-center space-y-2 hover:shadow-md transition text-center">
            <i class="fas fa-tools text-primary text-4xl"></i>
            <h2 class="text-gray-800 font-semibold">Pending Maintenances</h2>
            <p class="text-2xl font-bold text-gray-800">{{ $pendingMaintenances }}</p>
        </div>
    </div>

    <div id='calendar'></div>

    @script
        <script type="text/javascript">
            document.addEventListener('livewire:initialized', () => {
                var calendarEl = document.getElementById('calendar');

                var events = @json($events);

                console.log("Events Data: ", events); // ✅ Debugging outpu

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    events: @json($events),
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay' // month week day buttons
                    },
                    // eventClassNames: function(info) {
                    //     let classes = [];

                    //     // Assign base category styling
                    //     if (info.event.extendedProps.category === 'room') {
                    //         classes.push('room-booking');
                    //     } else if (info.event.extendedProps.category === 'event') {
                    //         classes.push('event-booking');
                    //     }

                    //     // Assign color based on transaction status
                    //     switch (info.event.extendedProps.transaction_status) {
                    //         case 'pending':
                    //             classes.push('status-pending');
                    //             break;
                    //         case 'confirmed':
                    //             classes.push('status-confirmed');
                    //             break;
                    //         case 'cancelled':
                    //             classes.push('status-cancelled');
                    //             break;
                    //         default:
                    //             classes.push('status-default');
                    //     }

                    //     return classes;
                    // }

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
