<?php

namespace App\Mail;

use App\Models\Suscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNextPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Suscription $subscription;

    public function __construct(Suscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio próximo cobro',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nextPayment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
