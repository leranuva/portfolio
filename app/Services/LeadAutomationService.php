<?php

namespace App\Services;

use App\Jobs\SendLeadFollowup;
use App\Models\Lead;
use Brevo\Brevo;
use Brevo\Contacts\Requests\CreateContactRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadAutomationService
{
    public function sync(Lead $lead): void
    {
        $this->sendWebhook($lead);
        $this->syncToBrevo($lead);
        $this->scheduleFollowups($lead);
    }

    protected function scheduleFollowups(Lead $lead): void
    {
        if (! config('lead_automation.followup_emails_enabled', true)) {
            return;
        }

        SendLeadFollowup::dispatch($lead, 1)->delay(now()->addDays(2));
        SendLeadFollowup::dispatch($lead, 2)->delay(now()->addDays(5));
        SendLeadFollowup::dispatch($lead, 3)->delay(now()->addDays(10));
    }

    protected function sendWebhook(Lead $lead): void
    {
        $url = config('lead_automation.webhook_url');

        if (blank($url)) {
            return;
        }

        try {
            $response = Http::timeout(10)
                ->post($url, [
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'project_type' => $lead->project_type,
                    'what_to_automate' => $lead->what_to_automate,
                    'budget_range' => $lead->budget_range,
                    'urgency' => $lead->urgency,
                    'message' => $lead->message,
                    'score' => $lead->score,
                    'quality' => $lead->quality,
                    'status' => $lead->status,
                    'created_at' => $lead->created_at->toIso8601String(),
                ]);

            if (! $response->successful()) {
                Log::warning('Lead webhook failed', [
                    'lead_id' => $lead->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Lead webhook exception', [
                'lead_id' => $lead->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function syncToBrevo(Lead $lead): void
    {
        $config = config('lead_automation.brevo');

        if (! ($config['enabled'] ?? false) || blank($config['api_key'])) {
            return;
        }

        $listId = (int) ($config['list_id'] ?? 0);
        if ($listId <= 0) {
            return;
        }

        try {
            $brevo = new Brevo($config['api_key']);

            // Create or update contact. Attributes FIRSTNAME, LASTNAME exist by default in Brevo.
            // Add custom attributes (PROJECT_TYPE, BUDGET, etc.) in Brevo dashboard if needed.
            $nameParts = explode(' ', $lead->name, 2);

            $brevo->contacts->createContact(
                new CreateContactRequest([
                    'email' => $lead->email,
                    'attributes' => array_filter([
                        'FIRSTNAME' => $nameParts[0] ?? $lead->name,
                        'LASTNAME' => $nameParts[1] ?? null,
                    ]),
                    'listIds' => [$listId],
                    'updateEnabled' => true,
                ])
            );
        } catch (\Throwable $e) {
            Log::error('Brevo lead sync failed', [
                'lead_id' => $lead->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
