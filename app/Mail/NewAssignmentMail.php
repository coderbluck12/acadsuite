<?php

namespace App\Mail;

use App\Models\Assignment;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAssignmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Assignment $assignment;
    public User $student;
    public Tenant $tenant;

    /**
     * Create a new message instance.
     */
    public function __construct(Assignment $assignment, User $student, Tenant $tenant)
    {
        $this->assignment = $assignment;
        $this->student = $student;
        $this->tenant = $tenant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Assignment Posted: ' . $this->assignment->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-assignment',
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
