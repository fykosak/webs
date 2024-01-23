import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
import iCalendarPlugin from '@fullcalendar/icalendar'
import csLocale from '@fullcalendar/core/locales/cs';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';


document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, iCalendarPlugin, listPlugin, bootstrap5Plugin],
        locale: csLocale,
        themeSystem: 'bootstrap5',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listYear'
        },
        navLinks: false,
        editable: false,
        dayMaxEvents: true,
        events: {
            url: '/events/getrawcalendar',
            format: 'ics'
        },
    });
    calendar.render();
});