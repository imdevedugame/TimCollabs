<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Jika Anda benar-benar butuh daftar semua komentar:
     */
    public function index()
    {
        // Misalnya: semua komentar, di-paginate
        $comments = Comment::with('user')->latest()->paginate(10);
        return view('comments.index', compact('comments'));
    }

    /**
     * Jika Anda butuh form buat komentar (tidak selalu perlu, kalau nested route).
     */
    public function create()
    {
        return view('comments.create');
    }

    /**
     * Simpan komentar (nested di route: POST /tasks/{task}/comments).
     * Menggunakan Route Model Binding: Task $task
     */
    public function store(Request $request, Task $task)
    {
        // Validasi input
        $request->validate([
            'message' => 'required|string',
            'title'   => 'nullable|string|max:255',
        ]);

        // Buat komentar di DB
        $comment = Comment::create([
            'task_id' => $task->id,        // diperoleh dari {task} param
            'user_id' => auth()->id(),     // user yang saat ini login
            'title'   => $request->title,  // opsional
            'message' => $request->message,
        ]);

        // Arahkan kembali ke halaman detail Tugas 
        // agar user dapat melihat komentar barunya
        return redirect()->route('tasks.show', $task->id)
                         ->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail komentar (hanya jika Anda mau /comments/{comment})
     */
    public function show(Comment $comment)
    {
        $comment->load('user');
        return view('comments.show', compact('comment'));
    }

    /**
     * Menampilkan form edit komentar (jika memang perlu).
     */
    public function edit(Comment $comment)
    {
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update komentar di DB.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'message' => 'required|string',
            'title'   => 'nullable|string|max:255',
        ]);

        $comment->update([
            'title'   => $request->title,
            'message' => $request->message,
        ]);

        // Jika komentar selalu terkait task, Anda bisa redirect ke tasks.show pula
        return redirect()->route('comments.show', $comment->id)
                         ->with('success', 'Komentar berhasil diupdate.');
    }

    /**
     * Hapus komentar
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        // Tergantung skenario, redirect ke index comment atau ke tasks.show
        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
