<?php

namespace App\Jobs;

use App\Mail\LeadFollowupMail;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Services\LeadEventService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLeadFollowup implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(
        public Lead $lead,
        public int $step
    ) {}

    public function handle(LeadEventService $eventService): void
    {
        $lead = $this->lead->fresh();
        if (! config('lead_automation.followup_emails_enabled') || ! $lead?->email_verified_at) {
            return;
        }

        Mail::to($lead->email)->send(new LeadFollowupMail($lead, $this->step));
        $eventService->record($lead, LeadEvent::TYPE_FOLLOWUP_SENT, ['step' => $this->step]);
    }
}
