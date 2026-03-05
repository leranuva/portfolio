<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\LeadAutomationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncLeadToEmailProvider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public Lead $lead
    ) {}

    public function handle(LeadAutomationService $service): void
    {
        $service->sync($this->lead);
    }
}
