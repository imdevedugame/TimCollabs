<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendInvitation extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'task_id', 'status'];

    // Relasi ke user pengirim undangan
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke user penerima undangan
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    
    // Relasi ke task terkait undangan
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
