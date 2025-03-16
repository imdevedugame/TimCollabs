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

        // Ambil tugas yang dimiliki (ownedTasks) dan yang diikutinya (tasks) dengan deadline tidak null
        $ownedTasks = $user->ownedTasks()->whereNotNull('deadline')->get();
        $memberTasks = $user->tasks()->whereNotNull('deadline')->get();

        // Gabungkan dan urutkan tugas berdasarkan deadline
        $tasks = $ownedTasks->merge($memberTasks)->sortBy('deadline');

        // Pastikan deadline sudah dikonversi ke instance Carbon (jika belum, gunakan Carbon::parse)
        $events = $tasks->map(function ($task) {
            // Jika casting di model sudah benar, $task->deadline seharusnya instance Carbon
            $deadline = $task->deadline instanceof Carbon
                ? $task->deadline
                : Carbon::parse($task->deadline);

            $color = match ($task->priority) {
                'high'   => '#dc3545',  // merah
                'medium' => '#ffc107',  // kuning
                default  => '#198754',  // hijau
            };

            return [
                'id'    => $task->id,
                'title' => $task->title,
                'start' => $deadline->format('Y-m-d H:i:s'),
                'color' => $color,
                'textColor' => '#fff',
                'extendedProps' => [
                    'priority' => $task->priority,
                    'deadline' => $deadline->format('d M Y H:i'),
                ],
            ];
        })->values(); // values() untuk mereset index collection

        return view('calendar.index', compact('events'));
    }
}
