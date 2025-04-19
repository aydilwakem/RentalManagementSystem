import './bootstrap';


document.addEventListener('alpine:init', () => {
    // Load sidebar state from localStorage (default to true if not set)
    const savedSidebarState = JSON.parse(localStorage.getItem('sidebar')) || {
        full: true, // Ensure it opens by default
        active: 'home',
        navOpen: false
    };

    Alpine.store('sidebar', {
        full: savedSidebarState.full,
        active: savedSidebarState.active,
        navOpen: savedSidebarState.navOpen,

        toggle() {
            this.full = !this.full;
            this.saveState();
        },

        setActive(tab) {
            this.active = tab;
            this.saveState();
        },

        saveState() {
            localStorage.setItem('sidebar', JSON.stringify({
                full: this.full,
                active: this.active,
                navOpen: this.navOpen
            }));
        }
    });

    // Creating component Dropdown
    Alpine.data('dropdown', () => ({
        open: false,

        toggle(tab) {
            this.open = !this.open;
            Alpine.store('sidebar').setActive(tab);
        },

        isActive(tab) {
            return Alpine.store('sidebar').active === tab ? 'bg-gray-800 text-gray-200' : '';
        },

        expandedClass: 'border-l border-gray-400 ml-4 pl-4',
        shrinkedClass: 'sm:absolute top-0 left-20 sm:shadow-md sm:z-10 sm:bg-gray-900 sm:rounded-md sm:p-4 border-l sm:border-none border-gray-400 ml-4 pl-4 sm:ml-0 w-28'
    }));

    // Creating component Sub Dropdown
    Alpine.data('sub_dropdown', () => ({
        sub_open: false,

        sub_toggle() {
            this.sub_open = !this.sub_open;
        },

        sub_expandedClass: 'border-l border-gray-400 ml-4 pl-4',
        sub_shrinkedClass: 'sm:absolute top-0 left-28 sm:shadow-md sm:z-10 sm:bg-gray-900 sm:rounded-md sm:p-4 border-l sm:border-none border-gray-400 ml-4 pl-4 sm:ml-0 w-28'
    }));
});



// document.addEventListener('DOMContentLoaded', function () {
//     const darkModeToggle = document.getElementById('dark-mode-toggle');
//     const textSizeSlider = document.getElementById('text-size');
//     const fontReset = document.getElementById('font-reset');
//     const settingsContent = document.getElementById('settings-content');
//     const webSettingsBtn = document.getElementById('web-settings');

//     // Load saved settings from localStorage
//     const savedFontSize = localStorage.getItem('font-size');
//     const savedDarkMode = localStorage.getItem('dark-mode');

//     // Apply saved font size if available
//     if (savedFontSize) {
//         document.body.style.fontSize = savedFontSize;
//         textSizeSlider.value = (parseFloat(savedFontSize) - 1) * 10; // Convert to slider value
//     }

//     // Apply saved dark mode state if available
//     if (savedDarkMode === 'true') {
//         document.documentElement.classList.add('dark');
//         darkModeToggle.checked = true;
//     }

//     // Toggle settings panel visibility
//     webSettingsBtn.addEventListener('click', () => {
//         settingsContent.classList.toggle('hidden');
//     });

//     // Adjust font size dynamically
//     textSizeSlider.addEventListener('input', () => {
//         const scale = 1 + (textSizeSlider.value / 10);
//         document.body.style.fontSize = `${scale}em`;
//         localStorage.setItem('font-size', `${scale}em`); // Save font size to localStorage
//     });

//     // Reset font size to default
//     fontReset.addEventListener('click', () => {
//         textSizeSlider.value = 0;
//         document.body.style.fontSize = '';
//         localStorage.removeItem('font-size'); // Remove font size from localStorage
//     });

//     // Toggle dark mode and save the state
//     darkModeToggle.addEventListener('change', () => {
//         if (darkModeToggle.checked) {
//             document.documentElement.classList.add('dark');
//             localStorage.setItem('dark-mode', 'true'); // Save dark mode state to localStorage
//         } else {
//             document.documentElement.classList.remove('dark');
//             localStorage.setItem('dark-mode', 'false'); // Save dark mode state to localStorage
//         }
//     });
// });


/**
 * document.addEventListener('livewire:initialized', () => {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
          events: @json($events),
        select: function (info) {
            console.log(info);
            var title = prompt("Enter event name: ");
            console.log(title);
            Livewire.dispatch("addEvent", {
                title: title,
                start: info.startStr,
                end: info.endStr
            });
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay' // month week day buttons
        }
    });

    calendar.render();

    Livewire.on('eventLoaded', (events) => {
        calendar.removeAllEvents();
        calendar.addEventSource(events);
        console.log(events);
    })



});
 */

