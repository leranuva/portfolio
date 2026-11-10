<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendlyWebhookService
{
    public function isAuthentic(Request $request, string $key): bool
    {
        $signature = $request->header('Calendly-Webhook-Signature', '');
        if (! preg_match('/\At=(\d{1,10}),\s*v1=([a-f0-9]{64})\z/', $signature, $parts)) {
            return false;
        }

        [, $timestamp, $digest] = $parts;

        return abs(now()->timestamp - (int) $timestamp) <= 180
            && hash_equals(hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $key), $digest);
    }

    public function recordBooking(string $email, string $inviteeUri): void
    {
        $bookingId = hash('sha256', $inviteeUri);

        DB::transaction(function () use ($email, $bookingId): void {
            // Serialize deliveries for the same booking, even if another lead was added since the first delivery.
            if (! DB::table('calendly_bookings')->insertOrIgnore(['id' => $bookingId, 'created_at' => now()])) {
                return;
            }

            $lead = Lead::where('email', $email)->latest()->orderByDesc('id')->lockForUpdate()->first();
            if (! $lead) {
                return;
            }

            if (in_array($lead->status, [Lead::STATUS_NUEVO, Lead::STATUS_CONTACTED, Lead::STATUS_EN_CONTACTO], true)) {
                $lead->update(['status' => Lead::STATUS_MEETING_SCHEDULED]);
            }

            app(LeadEventService::class)->record($lead, LeadEvent::TYPE_MEETING_BOOKED, ['booking_id' => $bookingId]);
        }, 3);
    }
}
