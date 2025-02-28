<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $ownedTasks = $user->ownedTasks()->whereNotNull('deadline')->get();
    $memberTasks = $user->tasks()->whereNotNull('deadline')->get();
    $tasks = $ownedTasks->merge($memberTasks)->sortBy('deadline');

    $events = $tasks->map(function ($task) {
        $color = match ($task->priority) {
            'high'   => '#dc3545',  // merah
            'medium' => '#ffc107',  // kuning
            default  => '#198754',  // hijau
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

    // Debugging untuk memastikan `$events` tidak kosong
    

    return view('calendar.index', compact('events'));
}

}