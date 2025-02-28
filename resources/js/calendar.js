import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // Styling untuk tooltip

document.addEventListener('DOMContentLoaded', () => {
  // Pastikan ada container dengan ID "taskCalendarContainer"
  const container = document.getElementById('taskCalendarContainer');
  if (!container) return;

  // Atur container agar tampil responsif, misalnya 90vw x 80vh, dan center secara horizontal
  container.style.height = "80vh";
  container.style.width = "70vw";
  container.style.margin = "2vh auto";
  container.style.display = "flex";
  container.style.flexDirection = "row"; // susun secara horizontal

  // Buat sidebar untuk filter dan kalender di sebelahnya
  container.innerHTML = `
  <div id="sidebar" style="
    width: 80px;
    background: linear-gradient(to bottom, #1a73e8, #4285f4);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem 0;
    box-sizing: border-box;
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
    position: relative;
">
  <!-- Tombol Filter dengan desain modern -->
  <button id="filterBtn" style="
      
      color:white;
      border: none;
      padding: 0.75rem;
      ;
      font-size: 1.4rem;
      cursor: pointer;
      
      transition: transform 0.2s ease, box-shadow 0.2s ease;
  ">
    <i class="fas fa-filter"></i>
  </button>
  <!-- Ruang kosong agar tombol tetap di atas -->
  <div style="flex-grow: 1;"></div>
  <!-- Dropdown Filter dengan desain yang rapi -->
  <div id="filterDropdown" style="
      display: none;
      position: absolute;
      left: 70px;
      top: 20px;
      background: #fff;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      border-radius: 10px;
      z-index: 1000;
      font-size: 0.9rem;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
  ">
    <div data-filter="all" style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f0f0f0;">All Tasks</div>
    <div data-filter="high" style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f0f0f0;">High Priority</div>
    <div data-filter="medium" style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f0f0f0;">Medium Priority</div>
    <div data-filter="low" style="padding: 0.5rem 1rem; cursor: pointer;">Low Priority</div>
  </div>
</div>
<div id="taskCalendar" style="flex-grow: 1; overflow: auto;"></div>

  `;

  // Tambahkan efek hover untuk tombol filter
  const filterBtn = document.getElementById('filterBtn');
  filterBtn.addEventListener('mouseenter', () => {
    filterBtn.style.transform = "scale(1.1)";
    filterBtn.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
  });
  filterBtn.addEventListener('mouseleave', () => {
    filterBtn.style.transform = "scale(1)";
    filterBtn.style.boxShadow = "none";
  });

  // Atur toggle dropdown filter
  const filterDropdown = document.getElementById('filterDropdown');
  filterBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    filterDropdown.style.display = filterDropdown.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', (e) => {
    if (!filterBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
      filterDropdown.style.display = 'none';
    }
  });

  // Filter aktif default: all
  let activeFilter = 'all';

  // Ambil data event dari Blade (pastikan window.calendarEvents berbentuk array)
  const allEvents = Array.isArray(window.calendarEvents) ? window.calendarEvents : [];

  // Inisialisasi FullCalendar pada elemen dengan ID "taskCalendar"
  const calendarEl = document.getElementById('taskCalendar');
  const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    events: filterEvents(activeFilter, allEvents),
    eventMouseEnter: (info) => {
      if (info.el._tippy) {
        info.el._tippy.destroy();
      }
      const { title } = info.event;
      const { priority, deadline, status, description } = info.event.extendedProps;

      const tooltipContent = `
        <div style="padding: 1rem; max-width: 300px; font-family: Arial, sans-serif;">
          <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: bold; color: #202124; font-size: 1rem; margin-bottom: 0.75rem;">
            <i class="fas fa-tasks" style="color: #5f6368;"></i>
            <span>${title}</span>
          </div>
          ${description ? `
            <div style="margin-bottom: 0.75rem; font-size: 0.875rem; color: #5f6368; border-left: 2px solid #dadce0; padding-left: 0.5rem; font-style: italic;">
              ${description}
            </div>
          ` : ''}
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem; color: #202124;">
              <i class="fas fa-calendar-alt" style="color: #5f6368;"></i>
              <span>Deadline: ${deadline}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem; color: #202124;">
              <i class="fas fa-exclamation-circle" style="color: #5f6368;"></i>
              <span style="background-color: ${getPriorityColor(priority)}; color: #fff; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                ${priority}
              </span>
            </div>
          </div>
          ${status ? `
            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem; color: #202124; margin-top: 0.5rem;">
              <i class="fas fa-info-circle" style="color: #5f6368;"></i>
              <span style="background-color: ${getStatusColor(status)}; color: #fff; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                ${status}
              </span>
            </div>
          ` : ''}
          <div style="margin-top: 0.75rem; padding-top: 0.5rem; border-top: 1px solid #dadce0; font-size: 0.75rem; color: #5f6368;">
            Click to view details
          </div>
        </div>
      `;

      tippy(info.el, {
        content: tooltipContent,
        allowHTML: true,
        theme: 'light-border',
        placement: 'top',
        interactive: true,
        appendTo: document.body,
        maxWidth: 320
      });
    },
    eventMouseLeave: (info) => {
      if (info.el._tippy) {
        info.el._tippy.destroy();
      }
    },
  });

  calendar.render();

  // Atur event klik untuk opsi filter
  const filterOptions = filterDropdown.querySelectorAll('div[data-filter]');
  filterOptions.forEach(option => {
    option.addEventListener('click', () => {
      activeFilter = option.getAttribute('data-filter');
      calendar.removeAllEventSources();
      calendar.addEventSource(filterEvents(activeFilter, allEvents));
      filterDropdown.style.display = 'none';
    });
  });
});

// Helper function untuk memfilter event
function filterEvents(filter, events) {
  if (filter === 'all') {
    return events.map(event => ({
      title: event.title,
      start: event.start,
      color: getPriorityColor(event.extendedProps.priority),
      extendedProps: event.extendedProps
    }));
  }
  return events
    .filter(event => event.extendedProps.priority?.toLowerCase() === filter)
    .map(event => ({
      title: event.title,
      start: event.start,
      color: getPriorityColor(event.extendedProps.priority),
      extendedProps: event.extendedProps
    }));
}

// Fungsi untuk menentukan warna berdasarkan prioritas
function getPriorityColor(priority) {
  switch (priority?.toLowerCase()) {
    case 'high': return '#d93025';
    case 'medium': return '#f9ab00';
    case 'low': return '#188038';
    default: return '#1a73e8';
  }
}

// Fungsi untuk menentukan warna badge status (jika ada)
function getStatusColor(status) {
  switch (status?.toLowerCase()) {
    case 'completed': return '#34a853';
    case 'overdue': return '#ea4335';
    case 'pending': return '#4285f4';
    default: return '#9aa0a6';
  }
}
