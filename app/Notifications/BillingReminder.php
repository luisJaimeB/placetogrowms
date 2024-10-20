<?php

namespace App\Notifications;

use App\Models\Suscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillingReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected Suscription $subscription;
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recordatorio de Cobro de Suscripción')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Este es un recordatorio de que tu suscripción será cobrada el día de mañana.')
            ->line('Detalles de la suscripción:')
            ->line('Plan: ' . $this->subscription->suscriptionPlan->name)
            ->line('Fecha de cobro: ' . $this->subscription->next_billing_date)
            ->line('Monto:' . $this->subscription->suscriptionPlan->amount)
            ->line('Gracias por usar nuestros servicios!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
