<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;
use Throwable;

class FirebaseNotificationService
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendPushNotification(User $user, string $title, string $body, string $url = null): bool
    {
        try {
            if (!$user->firebase_token) {
                throw new \InvalidArgumentException("User {$user->id} tidak memiliki FCM token");
            }

            $message = CloudMessage::withTarget('token', $user->firebase_token)
                ->withNotification(
                    Notification::create($title, $body)
                        ->withImageUrl(asset('images/logo.png'))  // Optional: tambahkan gambar
                )
                ->withData([
                    'url' => $url ?? route('home'),
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'  // Untuk integrasi mobile
                ]);

            $this->messaging->send($message);
            
            logger()->info("Notifikasi berhasil dikirim ke user {$user->id}");
            return true;

        } catch (Throwable $e) {
            logger()->error("Gagal mengirim notifikasi ke user {$user->id}: " . $e->getMessage());
            return false;
        }
    }
}