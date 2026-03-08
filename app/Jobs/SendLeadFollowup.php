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
        Mail::to($this->lead->email)->send(new LeadFollowupMail($this->lead, $this->step));
        $eventService->record($this->lead, LeadEvent::TYPE_FOLLOWUP_SENT, ['step' => $this->step]);
    }
}
