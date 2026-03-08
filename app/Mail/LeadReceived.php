<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public ?string $calendlyUrl = null,
        public ?string $senderName = null,
    ) {
        $this->calendlyUrl ??= SiteSetting::get('calendly_url') ?: url('/#calendly');
        $this->senderName ??= SiteSetting::get('hero_name', config('app.name'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks for your interest — ' . SiteSetting::get('hero_name', config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.lead-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
