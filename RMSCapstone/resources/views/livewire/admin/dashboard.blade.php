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
                    eventClassNames: function(info) {
                        // Dynamically assign class based on category
                        if (info.event.extendedProps.category === 'room') {
                            return ['room-booking'];
                        } else if (info.event.extendedProps.category === 'event') {
                            return ['event-booking'];
                        }
                    }
                });
                calendar.render();
            });
        </script>
    @endscript

</div>
