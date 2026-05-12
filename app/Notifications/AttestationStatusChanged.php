<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class AttestationStatusChanged extends Notification  
{
    use Queueable;

    public $attestation;

    public function __construct($attestation)
    {
        $this->attestation = $attestation;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    // ================= DATABASE =================
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'attestation',
            'title' => 'Attestation Status Changed',
            'message' => "Your attestation '{$this->attestation->type}' is now '{$this->attestation->status}'",
            'attestation_id' => $this->attestation->id,
        ];
    }

    // ================= BROADCAST =================
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'attestation',
            'title' => 'Attestation Status Changed',
            'message' => "Your attestation '{$this->attestation->type}' is now '{$this->attestation->status}'",
            'attestation_id' => $this->attestation->id,
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('doctor.' . $this->attestation->doctor->user_id);
    }

    public function broadcastAs()
    {
        return 'attestation.updated';
    }
}