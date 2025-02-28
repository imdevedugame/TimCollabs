<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarCardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil tugas yang dimiliki (owned) dan yang diikutinya (member), pastikan deadline tidak null
        $ownedTasks = $user->ownedTasks()->whereNotNull('deadline')->get();
        $memberTasks = $user->tasks()->whereNotNull('deadline')->get();
        $tasks = $ownedTasks->merge($memberTasks)->sortBy('deadline');

        $events = $tasks->map(function ($task) {
            $color = match ($task->priority) {
                'high'   => '#dc3545',  // Merah
                'medium' => '#ffc107',  // Kuning
                default  => '#198754',  // Hijau
            };

            return [
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
