<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    public function createNotification(User $user, string $type, array $data): void
    {
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => $type,
            'data' => $data,
        ]);
    }
}