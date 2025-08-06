<?php

namespace App\Notifications;

namespace App\Notifications;

use App\Models\Message; // N'oubliez pas d'importer le modèle
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageNotification extends Notification
{
    protected $message; // Déclarez la propriété

    // Injectez le message dans le constructeur
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database']; // Ajoutez 'database' si vous voulez stocker les notifications
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Nouveau message de ' . $this->message->sender->name,
            'body' => Str::limit($this->message->message, 50), // Limite à 50 caractères
            'conversation_id' => $this->message->conversation_id,
            'product_id' => $this->message->product_id, // Spécifique à l'agrobusiness
            'sender_avatar' => $this->message->sender->profile_photo_url // Si vous utilisez Spatie MediaLibrary
        ]);
    }

    // Optionnel : Format pour stockage en base
    public function toArray($notifiable)
    {
        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'product_name' => optional($this->message->product)->name // Graceful handling si null
        ];
    }
}
