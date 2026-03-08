<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Services\LeadEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalendlyWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = $request->input('event');
        $payload = $request->input('payload', []);

        if ($event !== 'invitee.created') {
            return response()->json(['received' => true]);
        }

        $email = $payload['invitee']['email'] ?? $payload['email'] ?? null;
        if (! $email && ($inviteeUri = $payload['uri'] ?? $payload['invitee']['uri'] ?? null)) {
            $email = $this->fetchInviteeEmail($inviteeUri);
        }

        if (! $email) {
            Log::warning('Calendly webhook: could not extract email', ['payload' => $payload]);

            return response()->json(['received' => true]);
        }

        $lead = Lead::where('email', $email)->latest()->first();
        if (! $lead) {
            Log::info('Calendly webhook: no lead found for email', ['email' => $email]);

            return response()->json(['received' => true]);
        }

        $lead->update(['status' => Lead::STATUS_MEETING_SCHEDULED]);
        app(LeadEventService::class)->record($lead, LeadEvent::TYPE_MEETING_BOOKED, []);

        return response()->json(['received' => true]);
    }

    protected function fetchInviteeEmail(string $inviteeUri): ?string
    {
        $token = config('lead_automation.calendly_api_token');
        if (blank($token)) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->accept('application/json')
                ->get($inviteeUri);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            return $data['resource']['email'] ?? $data['email'] ?? null;
        } catch (\Throwable $e) {
            Log::error('Calendly webhook API fetch', ['message' => $e->getMessage()]);

            return null;
        }
    }
}
