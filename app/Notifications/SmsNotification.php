<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message) {}

    public function via(object $notifiable): array
    {
        if (config('services.twilio.enabled')) {
            return ['vonage'];
        }

        return ['log'];
    }

    public function toVonage(object $notifiable): \Illuminate\Notifications\Messages\VonageMessage
    {
        return (new \Illuminate\Notifications\Messages\VonageMessage)
            ->content($this->message);
    }
}
