<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create([
            'title' => $request->title,
            'is_done' => false,
        ]);

        return redirect()->route('tasks.show', $task->id)
                         ->with('success', 'Subtask berhasil ditambahkan.');
    }

    public function update(Request $request, Subtask $subtask)
    {
        // Toggle is_done, atau update apapun
        $subtask->update([
            'is_done' => $request->has('is_done'),
             
        ]);

        return back()->with('success', 'Subtask berhasil diupdate.');
    }
}
