import { Calendar } from "@fullcalendar/core"
import dayGridPlugin from "@fullcalendar/daygrid"
import tippy from "tippy.js"
import "tippy.js/dist/tippy.css"
import "tippy.js/themes/light-border.css"

document.addEventListener("DOMContentLoaded", () => {
  const cardContainer = document.getElementById("taskCalendarCard")
  if (!cardContainer) return

  // Pastikan container memiliki posisi relative agar elemen absolute-nya terposisi dengan benar
  cardContainer.style.position = "relative"
  cardContainer.style.height = "300px" // pastikan tinggi card sesuai kebutuhan
  cardContainer.style.borderRadius = "12px"
  cardContainer.style.overflow = "hidden"
  cardContainer.style.boxShadow = "0 4px 12px rgba(0, 0, 0, 0.08)"
  cardContainer.style.fontFamily = "'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"

  // Sisipkan div baru sebagai kontainer kalender dengan header yang menarik
  cardContainer.innerHTML = `
    <div style="
      background: linear-gradient(135deg, #0052CC, #0047B3);
      color: white;
      padding: 12px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    ">
      <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-calendar-alt"></i>
        <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Kalender Tugas</h3>
      </div>
      <div id="calendar-nav" style="display: flex; gap: 8px;">
        <button id="prev-btn" style="
          background: rgba(255, 255, 255, 0.2);
          border: none;
          width: 24px;
          height: 24px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          color: white;
          font-size: 10px;
        ">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button id="next-btn" style="
          background: rgba(255, 255, 255, 0.2);
          border: none;
          width: 24px;
          height: 24px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          color: white;
          font-size: 10px;
        ">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
    <div id="calendarCard" style="height: calc(100% - 48px);"></div>
  `

  const calendarContainer = document.getElementById("calendarCard")
  const prevButton = document.getElementById("prev-btn")
  const nextButton = document.getElementById("next-btn")

  // Tambahkan CSS untuk styling FullCalendar
  const styleElement = document.createElement("style")
  styleElement.textContent = `
    .fc-theme-standard .fc-scrollgrid {
      border: none !important;
    }
    
    .fc .fc-daygrid-day-frame {
      padding: 2px;
    }
    
    .fc .fc-daygrid-day-top {
      justify-content: center;
    }
    
    .fc .fc-daygrid-day-number {
      font-size: 0.8rem;
      font-weight: 500;
      color: #333;
      padding: 2px;
    }
    
    .fc .fc-col-header-cell-cushion {
      font-weight: 600;
      color: #555;
      text-transform: uppercase;
      font-size: 0.7rem;
      padding: 6px 0;
    }
    
    .fc-theme-standard td, .fc-theme-standard th {
      border-color: #eaeaea;
    }
    
    .fc .fc-daygrid-day.fc-day-today {
      background-color: rgba(0, 82, 204, 0.05);
    }
    
    .fc-event {
      border-radius: 4px !important;
      border: none !important;
      padding: 2px 4px !important;
      font-size: 0.7rem !important;
      font-weight: 500 !important;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
      cursor: pointer !important;
      transition: transform 0.2s, box-shadow 0.2s !important;
    }
    
    .fc-event:hover {
      transform: translateY(-1px) !important;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) !important;
    }
    
    .fc-toolbar-title {
      font-size: 1rem !important;
      font-weight: 600 !important;
    }
    
    /* Tippy custom theme */
    .tippy-box[data-theme~='task-tooltip'] {
      background-color: white;
      color: #333;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
    }
    
    .tippy-box[data-theme~='task-tooltip'] .tippy-content {
      padding: 0;
    }
    
    .tippy-box[data-theme~='task-tooltip'] .tippy-arrow {
      color: white;
    }
  `
  document.head.appendChild(styleElement)

  // Ambil data event dari backend (dikirim dari controller via window.calendarEvents)
  const allEvents = Array.isArray(window.calendarEvents) ? window.calendarEvents : []

  // Inisialisasi FullCalendar
  const calendar = new Calendar(calendarContainer, {
    plugins: [dayGridPlugin],
    initialView: "dayGridMonth",
    headerToolbar: {
      left: "",
      center: "title",
      right: "", // tidak ada tombol view tambahan
    },
    contentHeight: "auto",
    dayMaxEventRows: 2,
    moreLinkContent: (args) => {
      return `+${args.num}`
    },
    events: allEvents.map((event) => ({
      id: event.id,
      title: event.title,
      start: event.start,
      color: getPriorityColor(event.extendedProps.priority),
      extendedProps: event.extendedProps,
    })),
    eventDidMount: (info) => {
      // Tambahkan ikon berdasarkan prioritas
      const eventEl = info.el
      const priority = info.event.extendedProps.priority?.toLowerCase() || "default"

      // Tambahkan ikon berdasarkan prioritas
      const iconElement = document.createElement("i")
      iconElement.className = getPriorityIcon(priority)
      iconElement.style.marginRight = "3px"
      iconElement.style.fontSize = "0.7rem"

      const titleEl = eventEl.querySelector(".fc-event-title")
      if (titleEl) {
        titleEl.prepend(iconElement)
      }
    },
    eventMouseEnter: (info) => {
      if (info.el._tippy) info.el._tippy.destroy()

      const { title } = info.event
      const { priority, deadline, status, description } = info.event.extendedProps

      // Format tanggal deadline jika ada
      let formattedDeadline = deadline
      if (deadline) {
        try {
          const deadlineDate = new Date(deadline)
          formattedDeadline = deadlineDate.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
          })
        } catch (e) {
          console.error("Error formatting date:", e)
        }
      }

      const tooltipContent = `
        <div style="overflow: hidden; border-radius: 8px;">
          <div style="background: ${getPriorityColor(priority)}; padding: 8px 12px; color: white;">
            <div style="font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 4px;">
              <i class="${getPriorityIcon(priority)}"></i>
              <span>${title}</span>
            </div>
          </div>
          
          <div style="padding: 8px 12px;">
            ${
              description
                ? `
              <div style="margin-bottom: 8px; font-size: 0.8rem; color: #555; border-left: 2px solid ${getPriorityColor(priority)}; padding-left: 6px;">
                ${description}
              </div>
            `
                : ""
            }
            
            <div style="font-size: 0.8rem; color: #555;">
              ${
                deadline
                  ? `
                <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 4px;">
                  <i class="fas fa-clock" style="color: #777; width: 12px;"></i>
                  <span>Deadline: <strong>${formattedDeadline}</strong></span>
                </div>
              `
                  : ""
              }
              
              ${
                status
                  ? `
                <div style="display: flex; align-items: center; gap: 4px;">
                  <i class="fas fa-info-circle" style="color: #777; width: 12px;"></i>
                  <span>Status: <span style="
                    display: inline-block;
                    padding: 2px 4px;
                    border-radius: 3px;
                    font-size: 0.7rem;
                    font-weight: 500;
                    background-color: ${getStatusColor(status)};
                    color: white;
                  ">${getStatusLabel(status)}</span></span>
                </div>
              `
                  : ""
              }
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
        maxWidth: 280,
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
  })

  calendar.render()

  // Tambahkan event listener untuk tombol navigasi
  prevButton.addEventListener("click", () => {
    calendar.prev()
  })

  nextButton.addEventListener("click", () => {
    calendar.next()
  })

  // Buat footer overlay untuk menampilkan tanggal dan info lain di pojok kanan bawah
  const footerDiv = document.createElement("div")
  footerDiv.style.position = "absolute"
  footerDiv.style.bottom = "8px"
  footerDiv.style.right = "8px"
  footerDiv.style.background = "rgba(255,255,255,0.9)"
  footerDiv.style.padding = "4px 8px"
  footerDiv.style.borderRadius = "4px"
  footerDiv.style.fontSize = "0.75rem"
  footerDiv.style.boxShadow = "0 1px 3px rgba(0,0,0,0.2)"
  footerDiv.style.display = "flex"
  footerDiv.style.alignItems = "center"
  footerDiv.style.gap = "6px"

  // Tampilkan tanggal hari ini dan "Other Info Asama" di sampingnya
  const today = new Date().toLocaleDateString("id-ID", {
    day: "numeric",
    month: "short",
    year: "numeric",
  })

  footerDiv.innerHTML = `
    <i class="fas fa-calendar-day" style="color: #0052CC;"></i>
    <span>${today}</span>
    <span style="margin-left:4px; padding-left: 4px; border-left: 1px solid #ddd;">Other Info Asama</span>
  `

  cardContainer.appendChild(footerDiv)
})

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

