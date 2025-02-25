<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        // Ambil tasks yang punya deadline
        $tasks = Task::whereNotNull('deadline')->get();

        // Ubah tasks ke bentuk event FullCalendar
        $events = $tasks->map(function ($task) {
            // Tentukan warna berdasarkan prioritas
            $color = match ($task->priority) {
                'high'   => '#dc3545',  // merah
                'medium' => '#ffc107',  // kuning
                default  => '#198754',  // hijau
            };

            return [
                // 'title' akan ditampilkan di kotak tanggal
                'title' => $task->title,

                // Tanggal mulai (deadline). Jika ingin juga jam:menit, pakai 'Y-m-d H:i:s'
                'start' => $task->deadline->format('Y-m-d H:i:s'),

                // Warna latar event
                'color' => $color,

                // Warna teks (supaya tidak tersamar)
                'textColor' => '#fff',

                // Data tambahan untuk tooltip
                'extendedProps' => [
                    'priority' => $task->priority,
                    'deadline' => $task->deadline->format('d M Y H:i'),
                ],
            ];
        });

        // Kirim $events ke view
        return view('calendar.index', compact('events'));
    }
}
