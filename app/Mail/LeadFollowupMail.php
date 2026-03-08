<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadFollowupMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public int $step,
        public ?string $calendlyUrl = null,
        public ?string $senderName = null,
    ) {
        $this->calendlyUrl ??= SiteSetting::get('calendly_url') ?: url('/#calendly');
        $this->senderName ??= SiteSetting::get('hero_name', config('app.name'));
    }

    public function envelope(): Envelope
    {
        $subjects = [
            1 => 'Quick follow-up — ' . SiteSetting::get('hero_name', config('app.name')),
            2 => '3 signs your business needs automation',
            3 => 'One last thing — free strategy call',
        ];

        return new Envelope(
            subject: $subjects[$this->step] ?? 'Follow-up',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.lead-followup',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
