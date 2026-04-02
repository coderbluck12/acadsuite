<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationCallLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $callLink,
        public string $bookingDate,
        public string $bookingTime,
        public string $academicName,
        public string $extraMessage = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Consultation Call Link – ' . $this->bookingDate . ' at ' . $this->bookingTime,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation-call-link',
        );
    }
}
