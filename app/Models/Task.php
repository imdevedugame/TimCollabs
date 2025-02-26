<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // menampung id pemilik task
        'title',
        'description',
        'priority',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    /**
     * Relasi many-to-many untuk task_user.
     * Menyimpan anggota-anggota yang terkait dengan task beserta pivot data (misalnya: role).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withTimestamps()
                    ->withPivot('role');
    }

    /**
     * Relasi one-to-many untuk pemilik task.
     * Setiap task dimiliki oleh satu user (owner).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Alias untuk relasi users(), agar lebih jelas bahwa ini adalah anggota task.
     */
    public function members()
    {
        return $this->users();
    }

    /**
     * (Opsional) Mengambil data anggota task (dari task_user) untuk user yang sedang login.
     * Jika user yang sedang login merupakan anggota task, maka akan mengembalikan model User beserta data pivot-nya.
     */
    public function authTaskUser()
    {
        return $this->users()->where('users.id', Auth::id())->first();
    }
}
