<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarCardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan data kalender.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();

        // Ambil semua Task yang punya deadline,
        // dan (dibuat oleh user) atau (user tercatat di pivot tasks_users).
        $tasks = Task::whereNotNull('deadline')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('users', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->get()
            ->sortBy('deadline'); // urut berdasarkan deadline

        // Mapping tiap Task → event FullCalendar
        $events = $tasks->map(function ($task) {
            // Tentukan warna berdasarkan prioritas
            $color = match ($task->priority) {
                'high'   => '#dc3545', // Merah
                'medium' => '#ffc107', // Kuning
                'low'    => '#198754', // Hijau
                default  => '#0052cc', // fallback: Biru
            };

            return [
                'id'         => $task->id,
                'title'      => $task->title,
                // Format "start" harus berupa string date/time sesuai standar JS
                // Gunakan optional() untuk hindari error jika deadline null
                'start'      => optional($task->deadline)->format('Y-m-d H:i:s'),
                'color'      => $color,
                'textColor'  => '#fff', // agar teks event jelas di atas warna
                'extendedProps' => [
                    'priority'    => $task->priority ?? 'low',
                    'deadline'    => optional($task->deadline)->format('d M Y H:i'),
                    'status'      => $task->status ?? 'N/A',
                    'description' => $task->description ?? '',
                ],
            ];
        })->values()->toArray();

        // Return view 'dashboard' dengan data $events
        return view('dashboard', compact('events'));
    }
}
