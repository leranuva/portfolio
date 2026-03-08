<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadEvent;

class LeadEventService
{
    public function record(Lead $lead, string $eventType, array $metadata = []): LeadEvent
    {
        return LeadEvent::create([
            'lead_id' => $lead->id,
            'event_type' => $eventType,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
