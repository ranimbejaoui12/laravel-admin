<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class LeaveRequestStatusChanged extends Notification 
{
    use Queueable;

    public $leaveRequest;

    public function __construct($leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    // ================= DATABASE =================
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'leave', // 🔥 IMPORTANT FIX

            'title' => 'Leave Request Updated',

            'message' => "Your leave request from {$this->leaveRequest->start_date} to {$this->leaveRequest->end_date} is {$this->leaveRequest->status}",

            'leave_request_id' => $this->leaveRequest->id,
        ];
    }

    // ================= BROADCAST =================
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'leave', // 🔥 IMPORTANT FIX

            'title' => 'Leave Request Updated',

            'message' => "Your leave request is {$this->leaveRequest->status}",

            'leave_request_id' => $this->leaveRequest->id,
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('doctor.' . $this->leaveRequest->doctor->user_id);
    }

    public function broadcastAs()
    {
        return 'leave.request.updated';
    }
}
