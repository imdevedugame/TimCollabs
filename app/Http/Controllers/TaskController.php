<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Ambil task yang user buat (owner)
    $ownedTasks = $user->ownedTasks()->orderBy('deadline', 'asc')->get();

    // Ambil task di mana user menjadi member
    $memberTasks = $user->tasks()->orderBy('deadline', 'asc')->get();

    // Gabungkan kedua collection dan urutkan berdasarkan deadline
    $tasks = $ownedTasks->merge($memberTasks)->sortBy('deadline');

    return view('tasks.index', compact('tasks'));
}
    
    public function create()
    {
        // Tampilkan form membuat tugas
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // Validasi form
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        // Simpan ke DB
        Task::create($request->all());

        // Kembali ke daftar tugas
        return redirect()->route('tasks.index')
                         ->with('success', 'Tugas berhasil dibuat.');
    }

    public function show(Task $task)
    {
        // Detail 1 tugas
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Tampilkan form edit tugas
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Validasi
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);

        // Update DB
        $task->update($request->all());

        return redirect()->route('tasks.index')
                         ->with('success', 'Tugas berhasil diupdate.');
    }

    public function destroy(Task $task)
    {
        if (! $task->users()->where('user_id', auth()->id())->where('role', 'owner')->exists()) {
            abort(403, 'Unauthorized');
        }
    
        $task->delete();
        return redirect()->route('tasks.index')
                         ->with('success', 'Tugas berhasil dihapus.');
    }
    public function inviteMember(Request $request, Task $task)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'role'  => 'nullable|string', // opsional
    ]);

    // Cari user berdasarkan email
    $user = \App\Models\User::where('email', $request->email)->first();

    // Attach user ke task
    $task->users()->attach($user->id, [
        'role' => $request->role ?? 'member',
    ]);

    return redirect()->route('tasks.show', $task->id)
                     ->with('success', 'Berhasil mengundang anggota.');
}

    
}
