<?php

namespace App\Mail;

use App\Models\Permit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermitStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Permit $permit;
    public string $oldStatus;
    public string $newStatus;
    public ?string $notes;

    /**
     * Create a new message instance.
     */
    public function __construct(Permit $permit, string $oldStatus, string $newStatus, ?string $notes = null)
    {
        $this->permit = $permit;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->notes = $notes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Permohonan Izin Reklame Diperbarui - ' . $this->permit->tracking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.permit-status-updated',
            with: [
                'permit' => $this->permit,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'notes' => $this->notes,
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
