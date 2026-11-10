<?php

namespace App\Http\Controllers;

use App\Services\CalendlyWebhookService;
use App\Services\PublicSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendlyWebhookController extends Controller
{
    public function __invoke(Request $request, CalendlyWebhookService $webhook): JsonResponse
    {
        $key = config('lead_automation.calendly_webhook_signing_key');
        abort_if(blank($key), 404);
        abort_if(strlen($request->getContent()) > 65536, 413);
        abort_unless($webhook->isAuthentic($request, $key), 401);

        // Use only the signed body. Request input can also include unsigned query values.
        $body = json_decode($request->getContent(), true);
        abort_unless(is_array($body) && is_string($body['event'] ?? null), 422);

        if ($body['event'] === 'invitee.created') {
            $validator = Validator::make($body, [
                'payload.email' => PublicSubmission::emailRules(),
                'payload.uri' => ['required', 'string', 'max:512', 'starts_with:https://api.calendly.com/scheduled_events/'],
            ]);
            abort_if($validator->fails(), 422);

            $webhook->recordBooking($body['payload']['email'], $body['payload']['uri']);
        }

        return response()->json(['received' => true]);
    }
}
