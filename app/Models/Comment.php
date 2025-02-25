<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NewCommentCreated; // Pastikan event ini sudah Anda buat

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'message',
    ];

    // Relasi ke model Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Laravel Eloquent Model Booting
     * Memicu broadcast event saat komentar baru dibuat
     */
    protected static function booted()
    {
        static::created(function ($comment) {
            // Setiap Comment baru, kita broadcast event “NewCommentCreated”
            broadcast(new NewCommentCreated($comment));
        });
    }
}
