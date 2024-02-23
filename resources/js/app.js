import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css'; // needs additional webpack config!

document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, bootstrap5Plugin ],
        themeSystem: 'bootstrap5',
        initialView: 'dayGridMonth',
        height: '90vh',
        firstDay: 1,
        weekends: false,
        fixedWeekCount: false,
        headerToolbar: {
            left: 'today',
            center: 'title',
            right: 'prev,next'
        },
        events: '/api/list',
        eventContent: function( info ) {
            return {html: info.event.title};
        },
        eventOrder: 'extendedProps.cohort',
        eventOrderStrict: true
    });
    calendar.render();
});