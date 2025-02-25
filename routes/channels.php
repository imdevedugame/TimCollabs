<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    // pastikan user punya relasi ke task
    return $user->tasks()->where('task_id', $taskId)->exists();
});

