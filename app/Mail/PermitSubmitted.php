<?php

namespace App\Mail;

use App\Models\Permit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermitSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Permit $permit;

    /**
     * Create a new message instance.
     */
    public function __construct(Permit $permit)
    {
        $this->permit = $permit;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permohonan Izin Reklame Anda Telah Diterima - ' . $this->permit->tracking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.permit-submitted',
            with: [
                'permit' => $this->permit,
                'trackingUrl' => url('/tracking/' . $this->permit->tracking_number),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
