<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = "myapp://reset-password?token=".$this->token."&email=".$notifiable->email;

        return (new MailMessage)
            ->subject('Reset Password')
            ->line('Click the button below to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.');
    }
}