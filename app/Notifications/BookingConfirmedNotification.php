<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $leg = $this->booking->flightLeg;

        return (new MailMessage)
            ->subject('Booking Confirmed — '.$this->booking->reference_number)
            ->greeting('Your helicopter booking is confirmed!')
            ->line('Reference: '.$this->booking->reference_number)
            ->line('Route: '.$leg->routeLabel())
            ->line('Departure: '.$leg->departure_at->format('l, F j Y \a\t g:i A'))
            ->line('Total: $'.number_format($this->booking->total_amount, 2))
            ->action('View Booking', url('/book/confirm/'.$this->booking->id));
    }
}
