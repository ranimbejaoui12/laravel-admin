<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class NewRequestForAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // ================= DATABASE =================
    public function toDatabase($notifiable)
    {
        $type = $this->data['type'] ?? null;

        // default url
        $url = route('requests.doctor');

        // redirect logic
        switch ($type) {

            case 'leave':
                $url = route('requests.doctor') . '?tab=leave';
                break;

            case 'attestation':
                $url = route('requests.doctor') . '?tab=attestation';
                break;

            case 'doctor_register':
                $url = route('users.index');
                break;
        }

        return [
            'type' => $type,
            'title' => $this->data['title'] ?? 'New Notification',
            'message' => $this->data['message'] ?? '',
            'doctor_id' => $this->data['doctor_id'] ?? null,
            'url' => $url,
        ];
    }

    // ================= BROADCAST =================
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => $this->data['type'] ?? null,
            'title' => $this->data['title'] ?? 'New Notification',
            'message' => $this->data['message'] ?? '',
            'doctor_id' => $this->data['doctor_id'] ?? null,
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin');
    }

    public function broadcastAs()
    {
        return 'new.request';
    }
}