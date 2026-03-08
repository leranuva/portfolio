<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HighIntentLeadReceived extends Mailable
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
            subject: 'Let\'s discuss your automation project — ' . SiteSetting::get('hero_name', config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.high-intent-lead-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
