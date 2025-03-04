import { Calendar } from "@fullcalendar/core"
import dayGridPlugin from "@fullcalendar/daygrid"
import tippy from "tippy.js"
import "tippy.js/dist/tippy.css"
import "tippy.js/themes/light-border.css"

document.addEventListener("DOMContentLoaded", () => {
  // Pastikan ada container dengan ID "taskCalendarContainer"
  const container = document.getElementById("taskCalendarContainer")
  if (!container) return

  // Atur container dengan styling modern
  container.style.height = "85vh"
  container.style.width = "100%"
  container.style.maxWidth = "1200px"
  container.style.margin = "2rem auto"
  container.style.display = "flex"
  container.style.flexDirection = "column"
  container.style.boxShadow = "0 10px 30px rgba(0, 0, 0, 0.1)"
  container.style.borderRadius = "16px"
  container.style.overflow = "hidden"
  container.style.backgroundColor = "#fff"
  container.style.fontFamily = "'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"

  // Buat struktur UI modern dengan header, toolbar, dan area kalender
  container.innerHTML = `
    <div class="calendar-header" style="
      background: linear-gradient(135deg, #0052CC, #0047B3);
      color: white;
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    ">
      <div class="calendar-title" style="display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-calendar-alt" style="font-size: 1.5rem;"></i>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 600;">Kalender Tugas</h2>
      </div>
      <div class="calendar-actions" style="display: flex; gap: 1rem; align-items: center;">
        <button id="today-btn" style="
          background: #FFB800;
          border: none;
          color: #333;
          padding: 0.5rem 1rem;
          border-radius: 8px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.2s;
          display: flex;
          align-items: center;
          gap: 0.5rem;
        ">
          <i class="fas fa-calendar-day"></i>
          Hari Ini
        </button>
      </div>
    </div>
    
    <div class="calendar-toolbar" style="
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      border-bottom: 1px solid #eaeaea;
    ">
      <div class="calendar-navigation" style="display: flex; gap: 0.5rem;">
        <button id="prev-btn" style="
          background: #f5f5f5;
          border: none;
          width: 36px;
          height: 36px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          transition: all 0.2s;
        ">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button id="next-btn" style="
          background: #f5f5f5;
          border: none;
          width: 36px;
          height: 36px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          transition: all 0.2s;
        ">
          <i class="fas fa-chevron-right"></i>
        </button>
        <div id="current-date-display" style="
          font-size: 1.1rem;
          font-weight: 600;
          margin-left: 1rem;
          display: flex;
          align-items: center;
        "></div>
      </div>
      
      <div class="filter-container" style="position: relative;">
        <button id="filter-btn" style="
          background: #f5f5f5;
          border: none;
          padding: 0.5rem 1rem;
          border-radius: 8px;
          display: flex;
          align-items: center;
          gap: 0.5rem;
          font-weight: 500;
          cursor: pointer;
          transition: all 0.2s;
        ">
          <i class="fas fa-filter"></i>
          <span>Filter</span>
          <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
        </button>
        
        <div id="filter-dropdown" style="
          display: none;
          position: absolute;
          right: 0;
          top: 100%;
          margin-top: 0.5rem;
          background: white;
          border-radius: 12px;
          box-shadow: 0 5px 20px rgba(0,0,0,0.15);
          z-index: 1000;
          min-width: 200px;
          overflow: hidden;
        ">
          <div class="filter-header" style="
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #666;
            border-bottom: 1px solid #eaeaea;
            font-size: 0.9rem;
          ">Filter Berdasarkan Prioritas</div>
          
          <div class="filter-options">
            <div data-filter="all" class="filter-option active" style="
              padding: 0.75rem 1rem;
              cursor: pointer;
              display: flex;
              align-items: center;
              gap: 0.75rem;
              transition: all 0.2s;
            ">
              <div style="
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #0052CC;
              "></div>
              <span>Semua Tugas</span>
            </div>
            
            <div data-filter="high" class="filter-option" style="
              padding: 0.75rem 1rem;
              cursor: pointer;
              display: flex;
              align-items: center;
              gap: 0.75rem;
              transition: all 0.2s;
            ">
              <div style="
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #E53935;
              "></div>
              <span>Prioritas Tinggi</span>
            </div>
            
            <div data-filter="medium" class="filter-option" style="
              padding: 0.75rem 1rem;
              cursor: pointer;
              display: flex;
              align-items: center;
              gap: 0.75rem;
              transition: all 0.2s;
            ">
              <div style="
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #FFB800;
              "></div>
              <span>Prioritas Sedang</span>
            </div>
            
            <div data-filter="low" class="filter-option" style="
              padding: 0.75rem 1rem;
              cursor: pointer;
              display: flex;
              align-items: center;
              gap: 0.75rem;
              transition: all 0.2s;
            ">
              <div style="
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #43A047;
              "></div>
              <span>Prioritas Rendah</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div id="taskCalendar" style="
      flex-grow: 1;
      padding: 1rem 2rem 2rem;
      overflow: auto;
    "></div>
  `

  // Tambahkan CSS untuk styling FullCalendar
  const styleElement = document.createElement("style")
  styleElement.textContent = `
    .fc-theme-standard .fc-scrollgrid {
      border: none !important;
    }
    
    .fc .fc-daygrid-day-frame {
      padding: 8px;
    }
    
    .fc .fc-daygrid-day-top {
      justify-content: center;
      padding-bottom: 5px;
    }
    
    .fc .fc-daygrid-day-number {
      font-size: 1rem;
      font-weight: 500;
      color: #333;
    }
    
    .fc .fc-col-header-cell-cushion {
      font-weight: 600;
      color: #555;
      text-transform: uppercase;
      font-size: 0.85rem;
      padding: 1rem 0;
    }
    
    .fc-theme-standard td, .fc-theme-standard th {
      border-color: #eaeaea;
    }
    
    .fc .fc-daygrid-day.fc-day-today {
      background-color: rgba(0, 82, 204, 0.05);
    }
    
    .fc-event {
      border-radius: 6px !important;
      border: none !important;
      padding: 3px 8px !important;
      font-size: 0.85rem !important;
      font-weight: 500 !important;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
      cursor: pointer !important;
      transition: transform 0.2s, box-shadow 0.2s !important;
    }
    
    .fc-event:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    }
    
    .filter-option:hover {
      background-color: #f5f7fa;
    }
    
    .filter-option.active {
      background-color: #f0f4ff;
      font-weight: 500;
    }
    
    #prev-btn:hover, #next-btn:hover {
      background-color: #e5e5e5;
    }
    
    #today-btn:hover {
      background-color: #e6a600;
    }
    
    #filter-btn:hover {
      background-color: #e5e5e5;
    }
    
    /* Tippy custom theme */
    .tippy-box[data-theme~='task-tooltip'] {
      background-color: white;
      color: #333;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      max-width: 350px !important;
    }
    
    .tippy-box[data-theme~='task-tooltip'] .tippy-content {
      padding: 0;
    }
    
    .tippy-box[data-theme~='task-tooltip'] .tippy-arrow {
      color: white;
    }
  `
  document.head.appendChild(styleElement)

  // Ambil data event dari Blade (pastikan window.calendarEvents berbentuk array)
  const allEvents = Array.isArray(window.calendarEvents) ? window.calendarEvents : []

  // Filter aktif default: all
  let activeFilter = "all"

  // Inisialisasi FullCalendar pada elemen dengan ID "taskCalendar"
  const calendarEl = document.getElementById("taskCalendar")
  const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin],
    initialView: "dayGridMonth",
    headerToolbar: false, // Kita menggunakan custom header
    height: "100%",
    dayMaxEvents: 3,
    firstDay: 1, // Mulai dari hari Senin
    events: transformEvents(allEvents, activeFilter),
    eventDidMount: (info) => {
      // Tambahkan badge prioritas pada event
      const eventEl = info.el
      const priority = info.event.extendedProps.priority?.toLowerCase() || "default"

      // Tambahkan ikon berdasarkan prioritas
      const iconElement = document.createElement("i")
      iconElement.className = getPriorityIcon(priority)
      iconElement.style.marginRight = "4px"
      iconElement.style.fontSize = "0.85rem"

      const titleEl = eventEl.querySelector(".fc-event-title")
      if (titleEl) {
        titleEl.prepend(iconElement)
      }
    },
    eventMouseEnter: (info) => {
      if (info.el._tippy) {
        info.el._tippy.destroy()
      }

      const { title } = info.event
      const { priority, deadline, status, description } = info.event.extendedProps

      // Format tanggal deadline jika ada
      let formattedDeadline = deadline
      if (deadline) {
        try {
          const deadlineDate = new Date(deadline)
          formattedDeadline = deadlineDate.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
          })
        } catch (e) {
          console.error("Error formatting date:", e)
        }
      }

      const tooltipContent = `
        <div style="overflow: hidden; border-radius: 12px;">
          <div style="background: ${getPriorityColor(priority)}; padding: 1rem; color: white;">
            <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
              <i class="${getPriorityIcon(priority)}"></i>
              <span>${title}</span>
            </div>
            <div style="font-size: 0.85rem; opacity: 0.9;">
              ${getPriorityLabel(priority)}
            </div>
          </div>
          
          <div style="padding: 1rem;">
            ${
              description
                ? `
              <div style="margin-bottom: 1rem; font-size: 0.9rem; color: #555; border-left: 3px solid ${getPriorityColor(priority)}; padding-left: 0.75rem;">
                ${description}
              </div>
            `
                : ""
            }
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
              ${
                deadline
                  ? `
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #555;">
                  <i class="fas fa-clock" style="color: #777; width: 16px;"></i>
                  <span>Deadline: <strong>${formattedDeadline}</strong></span>
                </div>
              `
                  : ""
              }
              
              ${
                status
                  ? `
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #555;">
                  <i class="fas fa-info-circle" style="color: #777; width: 16px;"></i>
                  <span>Status: <span style="
                    display: inline-block;
                    padding: 0.25rem 0.5rem;
                    border-radius: 4px;
                    font-size: 0.8rem;
                    font-weight: 500;
                    background-color: ${getStatusColor(status)};
                    color: white;
                  ">${getStatusLabel(status)}</span></span>
                </div>
              `
                  : ""
              }
            </div>
            
            <div style="
              margin-top: 1rem;
              padding-top: 0.75rem;
              border-top: 1px solid #eee;
              font-size: 0.8rem;
              color: #777;
              display: flex;
              align-items: center;
              justify-content: center;
              gap: 0.5rem;
            ">
              <i class="fas fa-mouse-pointer"></i>
              <span>Klik untuk melihat detail</span>
            </div>
          </div>
        </div>
      `

      tippy(info.el, {
        content: tooltipContent,
        allowHTML: true,
        theme: "task-tooltip",
        placement: "top",
        interactive: true,
        appendTo: document.body,
        maxWidth: 350,
        animation: "shift-away",
        duration: [200, 150],
      })
    },
    eventMouseLeave: (info) => {
      if (info.el._tippy) {
        info.el._tippy.destroy()
      }
    },
    eventClick: (info) => {
      // Redirect ke halaman detail tugas
      const taskId = info.event.id
      if (taskId) {
        window.location.href = `/tasks/${taskId}`
      }
    },
    datesSet: (dateInfo) => {
      // Update tampilan tanggal saat ini
      const dateDisplay = document.getElementById("current-date-display")
      if (dateDisplay) {
        const formattedDate = dateInfo.start.toLocaleDateString("id-ID", {
          month: "long",
          year: "numeric",
        })
        dateDisplay.textContent = formattedDate
      }
    },
  })

  calendar.render()

  // Atur event listener untuk tombol navigasi
  document.getElementById("prev-btn").addEventListener("click", () => {
    calendar.prev()
  })

  document.getElementById("next-btn").addEventListener("click", () => {
    calendar.next()
  })

  document.getElementById("today-btn").addEventListener("click", () => {
    calendar.today()
  })

  // Atur toggle dropdown filter
  const filterBtn = document.getElementById("filter-btn")
  const filterDropdown = document.getElementById("filter-dropdown")

  filterBtn.addEventListener("click", (e) => {
    e.stopPropagation()
    filterDropdown.style.display = filterDropdown.style.display === "block" ? "none" : "block"
  })

  document.addEventListener("click", (e) => {
    if (!filterBtn.contains(e.target) && !filterDropdown.contains(e.target)) {
      filterDropdown.style.display = "none"
    }
  })

  // Atur event listener untuk opsi filter
  const filterOptions = document.querySelectorAll(".filter-option")
  filterOptions.forEach((option) => {
    option.addEventListener("click", () => {
      const filter = option.getAttribute("data-filter")
      if (filter) {
        activeFilter = filter

        // Update active state
        filterOptions.forEach((opt) => opt.classList.remove("active"))
        option.classList.add("active")

        // Update events
        calendar.removeAllEventSources()
        calendar.addEventSource(transformEvents(allEvents, activeFilter))

        // Tutup dropdown
        filterDropdown.style.display = "none"
      }
    })
  })
})

// Helper function untuk memfilter dan mentransformasi event
function transformEvents(events, filter) {
  if (!Array.isArray(events)) return []

  let filteredEvents = events

  if (filter !== "all") {
    filteredEvents = events.filter((event) => event.extendedProps?.priority?.toLowerCase() === filter)
  }

  return filteredEvents.map((event) => ({
    id: event.id,
    title: event.title,
    start: event.start,
    end: event.end,
    allDay: event.allDay,
    color: getPriorityColor(event.extendedProps?.priority),
    textColor: "#fff",
    extendedProps: event.extendedProps || {},
  }))
}

// Fungsi untuk menentukan warna berdasarkan prioritas
function getPriorityColor(priority) {
  switch (priority?.toLowerCase()) {
    case "high":
      return "#E53935" // Merah
    case "medium":
      return "#FFB800" // Kuning
    case "low":
      return "#43A047" // Hijau
    default:
      return "#0052CC" // Biru
  }
}

// Fungsi untuk menentukan ikon berdasarkan prioritas
function getPriorityIcon(priority) {
  switch (priority?.toLowerCase()) {
    case "high":
      return "fas fa-exclamation-circle"
    case "medium":
      return "fas fa-exclamation"
    case "low":
      return "fas fa-check-circle"
    default:
      return "fas fa-tasks"
  }
}

// Fungsi untuk menentukan label prioritas
function getPriorityLabel(priority) {
  switch (priority?.toLowerCase()) {
    case "high":
      return "Prioritas Tinggi"
    case "medium":
      return "Prioritas Sedang"
    case "low":
      return "Prioritas Rendah"
    default:
      return "Tugas"
  }
}

// Fungsi untuk menentukan warna badge status
function getStatusColor(status) {
  switch (status?.toLowerCase()) {
    case "completed":
      return "#43A047" // Hijau
    case "in_progress":
      return "#1E88E5" // Biru
    case "pending":
      return "#7E57C2" // Ungu
    case "overdue":
      return "#E53935" // Merah
    default:
      return "#757575" // Abu-abu
  }
}

// Fungsi untuk menentukan label status
function getStatusLabel(status) {
  switch (status?.toLowerCase()) {
    case "completed":
      return "Selesai"
    case "in_progress":
      return "Dalam Proses"
    case "pending":
      return "Tertunda"
    case "overdue":
      return "Terlambat"
    default:
      return status
  }
}

