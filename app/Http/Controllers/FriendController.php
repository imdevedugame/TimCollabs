<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FriendInvitation;
use App\Models\Task;

class FriendController extends Controller
{
    // Menampilkan halaman undangan dan daftar teman
    public function index()
    {
        $user = Auth::user();

        // Ambil undangan yang masih pending untuk user yang sedang login
        $pendingInvitations = FriendInvitation::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        // Ambil semua undangan yang sudah diterima (accepted)
        // di mana user terlibat sebagai pengirim atau penerima
        $acceptedInvitations = FriendInvitation::where(function($query) use ($user) {
            $query->where('receiver_id', $user->id)
                  ->orWhere('sender_id', $user->id);
        })->where('status', 'accepted')
        ->with('sender', 'receiver')
        ->get();

        // Buat daftar teman dari undangan yang sudah diterima.
        // Jika user adalah pengirim, maka teman adalah receiver, dan sebaliknya.
        $friends = $acceptedInvitations->map(function($invitation) use ($user) {
            return $invitation->sender_id == $user->id ? $invitation->receiver : $invitation->sender;
        })->unique('id'); // Pastikan tidak ada duplikasi

        return view('friends.index', compact('pendingInvitations', 'friends'));
    }

    // Terima undangan
    public function accept($id)
    {
        $user = auth()->user();
        $invitation = \App\Models\FriendInvitation::findOrFail($id);
    
        // Pastikan undangan ditujukan untuk user yang sedang login
        if ($invitation->receiver_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    
        // Ubah status undangan menjadi accepted
        $invitation->update(['status' => 'accepted']);
    
        // Ambil task terkait undangan
        $task = $invitation->task;
    
        // Cek apakah user sudah terdaftar di task_user, jika belum, attach user ke task
        if (! $task->users()->where('user_id', $user->id)->exists()) {
            $task->users()->attach($user->id, ['role' => 'member']);
        }
    
        return redirect()->route('friends.index')->with('success', 'Undangan berhasil diterima. User sudah ditambahkan ke task.');
    }
    

    // Tolak undangan
    public function reject($id)
    {
        $user = Auth::user();
        $invitation = FriendInvitation::findOrFail($id);

        // Pastikan user yang login adalah penerima undangan
        if ($invitation->receiver_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Menghapus record undangan atau bisa juga update status menjadi 'rejected'
        $invitation->delete();

        return redirect()->route('friends.index')->with('success', 'Undangan berhasil ditolak.');
    }
}
