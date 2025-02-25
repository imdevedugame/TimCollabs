import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css' // agar tooltip ter-styling

document.addEventListener('DOMContentLoaded', () => {
  let calendarEl = document.getElementById('taskCalendar')

  if (calendarEl) {
    let calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin],
      initialView: 'dayGridMonth',

      // Data event dikirim dari Blade via window.calendarEvents
      events: window.calendarEvents,

      // Tampilkan tooltip saat hover
      eventMouseEnter: (info) => {
        let title    = info.event.title
        let priority = info.event.extendedProps.priority
        let deadline = info.event.extendedProps.deadline

        let tooltipContent = `
          <strong>Judul:</strong> ${title}<br>
          <strong>Prioritas:</strong> ${priority}<br>
          <strong>Deadline:</strong> ${deadline}
        `
        // Gunakan Tippy
        tippy(info.el, {
          content: tooltipContent,
          allowHTML: true,
          theme: 'light-border',
        })
      },

      // Hapus attribute bawaan agar tidak tabrakan
      eventMouseLeave: (info) => {
        info.el.removeAttribute('title')
      },
    })

    calendar.render()
  }
})
