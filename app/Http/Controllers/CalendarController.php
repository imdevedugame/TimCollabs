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

        // Ambil tasks yang dimiliki user (one-to-many) dengan deadline
        $ownedTasks = $user->ownedTasks()->whereNotNull('deadline')->get();

        // Ambil tasks dimana user adalah member (many-to-many) dengan deadline
        $memberTasks = $user->tasks()->whereNotNull('deadline')->get();

        // Gabungkan kedua collection dan urutkan berdasarkan deadline
        $tasks = $ownedTasks->merge($memberTasks)->sortBy('deadline');

        // Ubah tasks ke bentuk event untuk FullCalendar
        $events = $tasks->map(function ($task) {
            // Tentukan warna berdasarkan prioritas
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

        return view('calendar.index', compact('events'));
    }
}
