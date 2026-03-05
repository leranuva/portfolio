<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $message
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact message: ' . ($this->message->subject ?: 'No subject'),
            replyTo: [$this->message->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-message-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
