<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FriendInvitation; 
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date',
        ]);
    
        // Sertakan user_id dari user yang sedang login
        $validated['user_id'] = auth()->id();
    
        Task::create($validated);
    
        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dibuat.');
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
        'role'  => 'nullable|string',
    ]);

    // Cari user berdasarkan email
    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user) {
        return redirect()->route('tasks.show', $task->id)
                         ->with('error', 'User tidak ditemukan.');
    }

    // Cek apakah sudah ada undangan untuk task ini
    $existingInvitation = \App\Models\FriendInvitation::where('task_id', $task->id)
                            ->where('receiver_id', $user->id)
                            ->first();

    if ($existingInvitation) {
        if ($existingInvitation->status === 'pending') {
            return redirect()->route('tasks.show', $task->id)
                             ->with('error', 'Undangan sudah dikirim dan masih pending.');
        } elseif ($existingInvitation->status === 'accepted') {
            return redirect()->route('tasks.show', $task->id)
                             ->with('error', 'User sudah menjadi anggota.');
        } else { // rejected atau status lain
            return redirect()->route('tasks.show', $task->id)
                             ->with('error', 'Undangan sebelumnya telah ditolak.');
        }
    }

    // Jika validasi lolos, buat undangan baru
    \App\Models\FriendInvitation::create([
        'sender_id'   => auth()->id(),
        'receiver_id' => $user->id,
        'task_id'     => $task->id,
        'status'      => 'pending',
    ]);

    return redirect()->route('tasks.show', $task->id)
                     ->with('success', 'Berhasil mengundang anggota.');
}
public function archivedTasks(Request $request)
{
    $archivedTasks = Task::where('deadline', '<', now())
    ->where(function ($query) {
        $query->whereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })
        ->orWhere('user_id', auth()->id());
    })
    ->paginate(10);

    return view('arsip.index', compact('archivedTasks'));
}


    
}
