import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

document.addEventListener('DOMContentLoaded', () => {
  const cardContainer = document.getElementById('taskCalendarCard');
  if (!cardContainer) return;

  // Pastikan container memiliki posisi relative agar elemen absolute-nya terposisi dengan benar
  cardContainer.style.position = 'relative';
  cardContainer.style.height = "300px"; // pastikan tinggi card sesuai kebutuhan

  // Sisipkan div baru sebagai kontainer kalender
  cardContainer.innerHTML = `<div id="calendarCard" style="height: 100%;"></div>`;
  const calendarContainer = document.getElementById('calendarCard');

  // Ambil data event dari backend (dikirim dari controller via window.calendarEvents)
  const allEvents = Array.isArray(window.calendarEvents) ? window.calendarEvents : [];

  // Inisialisasi FullCalendar
  const calendar = new Calendar(calendarContainer, {
    plugins: [dayGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: '' // tidak ada tombol view tambahan
    },
    contentHeight: 250,
    dayMaxEventRows: 3,
  moreLinkContent: 'See more',
    events: allEvents.map(event => ({
      title: event.title,
      start: event.start,
      color: getPriorityColor(event.extendedProps.priority),
      extendedProps: event.extendedProps
    })),
    eventMouseEnter: (info) => {
      if (info.el._tippy) info.el._tippy.destroy();
      const { title } = info.event;
      const { priority, deadline } = info.event.extendedProps;
      const tooltipContent = `
        <div style="padding: 0.5rem; font-family: Arial, sans-serif;">
          <strong>${title}</strong><br>
          Priority: ${priority}<br>
          Deadline: ${deadline}
        </div>
      `;
      tippy(info.el, {
        content: tooltipContent,
        allowHTML: true,
        theme: 'light-border',
        placement: 'top',
        interactive: true,
        appendTo: document.body,
        maxWidth: 300
      });
    },
    eventMouseLeave: (info) => {
      if (info.el._tippy) info.el._tippy.destroy();
    },
  });

  calendar.render();

  // Buat footer overlay untuk menampilkan tanggal dan info lain di pojok kanan bawah
  const footerDiv = document.createElement('div');
  footerDiv.style.position = 'absolute';
  footerDiv.style.bottom = '5px';
  footerDiv.style.right = '5px';
  footerDiv.style.background = 'rgba(255,255,255,0.9)';
  footerDiv.style.padding = '4px 8px';
  footerDiv.style.borderRadius = '4px';
  footerDiv.style.fontSize = '0.85rem';
  footerDiv.style.boxShadow = '0 1px 3px rgba(0,0,0,0.2)';
  
  // Tampilkan tanggal hari ini dan "Other Info Asama" di sampingnya
  const today = new Date().toLocaleDateString();
  footerDiv.innerHTML = `<span>${today}</span><span style="margin-left:10px;">Other Info Asama</span>`;
  
  cardContainer.appendChild(footerDiv);
});

function getPriorityColor(priority) {
  switch (priority?.toLowerCase()) {
    case 'high': return '#d93025';
    case 'medium': return '#f9ab00';
    case 'low': return '#188038';
    default: return '#1a73e8';
  }
}
