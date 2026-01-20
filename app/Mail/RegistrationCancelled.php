<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registration Cancelled',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-cancelled',
            with: ['course' => $this->course]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}