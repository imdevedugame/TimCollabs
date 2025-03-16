<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;

class CalendarCardController extends Controller
{
    public function index(): View {
        $user = Auth::user();
    
        // Ambil semua tugas yang memiliki deadline (tidak null)
        // dan yang dibuat oleh user atau yang memiliki relasi di pivot (task_user)
        $tasks = Task::whereNotNull('deadline')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('users', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->get()
            ->sortBy('deadline');
    
        // Mapping event sesuai dengan FullCalendar
        $events = $tasks->map(function ($task) {
            $color = match ($task->priority) {
                'high'   => '#dc3545',  // Merah
                'medium' => '#ffc107',  // Kuning
                default  => '#198754',  // Hijau
            };
    
            return [
                'id'    => $task->id,
                'title' => $task->title,
                'start' => $task->deadline->format('Y-m-d H:i:s'),
                'color' => $color,
                'textColor' => '#fff',
                'extendedProps' => [
                    'priority' => $task->priority,
                    'deadline' => $task->deadline->format('d M Y H:i'),
                ],
            ];
        });
    
        return view('dashboard', compact('events'));
    }
}    
