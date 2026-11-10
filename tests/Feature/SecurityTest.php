<?php

namespace Tests\Feature;

use App\Jobs\SendLeadFollowup;
use App\Jobs\SyncLeadToEmailProvider;
use App\Mail\ContactMessageReceived;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_saves_without_outbound_email_and_deduplicates_retries(): void
    {
        Mail::fake();
        Queue::fake();

        $payload = $this->contactPayload(['project_type' => str_repeat('x', 255)]);
        $this->post('/contact-form', $payload)->assertRedirect(route('home').'#contact')->assertSessionHas('contact_success');
        $this->post('/contact-form', $payload)->assertSessionHas('contact_success');

        $this->assertDatabaseCount('leads', 1);
        $this->assertDatabaseCount('contact_messages', 1);
        $this->assertDatabaseCount('lead_events', 1);
        Mail::assertNothingOutgoing();
        Queue::assertNothingPushed();
    }

    public function test_opt_in_notification_has_fixed_recipient_and_headers(): void
    {
        Mail::fake();
        config(['contact.notify_admin' => true]);
        SiteSetting::set('contact_email', 'inbox@example.com');

        $this->post('/contact-form', $this->contactPayload())->assertSessionHas('contact_success');

        Mail::assertSentCount(1);
        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail): bool {
            return $mail->hasTo('inbox@example.com') && $mail->envelope()->replyTo === [];
        });
    }

    public function test_contact_rejects_header_injection_invalid_tracking_and_honeypot(): void
    {
        $this->postJson('/contact-form', $this->contactPayload([
            'email' => "lead@example.com\r\n Bcc: other@example.com",
            'utm_source' => ['invalid'],
            'company_website' => 'https://example.com',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['email', 'utm_source', 'company_website']);

        $this->get('/?utm_source[]=invalid')->assertOk();
        $this->assertDatabaseEmpty('leads');
    }

    public function test_contact_endpoints_share_the_recipient_rate_limit(): void
    {
        $payload = $this->contactPayload(['message' => 'Hello']);

        $this->postJson('/contact', $payload)->assertOk();
        $this->postJson('/contact', $payload)->assertOk();
        $this->post('/contact-form', $payload)->assertSessionHas('contact_success');
        $this->postJson('/contact', $payload)->assertTooManyRequests();

        $this->assertDatabaseCount('contact_messages', 2);
    }

    public function test_turnstile_requires_success_for_the_expected_hostname_and_action(): void
    {
        config([
            'app.url' => 'https://portfolio.example.com',
            'contact.turnstile.enabled' => true,
            'contact.turnstile.secret' => 'test-secret',
        ]);
        Http::preventStrayRequests();
        Http::fake([
            'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::sequence()
                ->push(['success' => true, 'hostname' => 'other.example.com', 'action' => 'portfolio_contact'])
                ->push(['success' => true, 'hostname' => 'portfolio.example.com', 'action' => 'portfolio_contact']),
        ]);

        $this->postJson('/contact-form', $this->contactPayload())->assertJsonValidationErrors('cf-turnstile-response');
        $payload = $this->contactPayload(['cf-turnstile-response' => 'test-token']);
        $this->postJson('/contact-form', $payload)->assertJsonValidationErrors('cf-turnstile-response');
        $this->assertDatabaseEmpty('leads');
        $this->postJson('/contact-form', $payload)->assertSessionHas('contact_success');
        Http::assertSentCount(2);
    }

    public function test_lead_magnet_deduplicates_and_limits_public_livewire_actions(): void
    {
        $form = Livewire::test('lead-magnet-form', ['resourceSlug' => 'auditoria']);
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $form->set('email', 'lead@example.com')->call('submit')->assertHasNoErrors();
        }

        $form->set('email', 'lead@example.com')->call('submit')->assertHasErrors('email');
        $this->assertDatabaseCount('leads', 1);
    }

    public function test_existing_jobs_do_not_process_unverified_leads(): void
    {
        config(['lead_automation.followup_emails_enabled' => true, 'lead_automation.webhook_url' => 'https://automation.example.com']);
        Mail::fake();
        Http::preventStrayRequests();
        Http::fake();
        $this->post('/contact-form', $this->contactPayload());
        $lead = Lead::sole();

        dispatch_sync(new SendLeadFollowup($lead, 1));
        dispatch_sync(new SyncLeadToEmailProvider($lead));

        Mail::assertNothingOutgoing();
        Http::assertNothingSent();
    }

    public function test_calendly_fails_closed_and_only_records_a_signed_booking_once(): void
    {
        Http::preventStrayRequests();
        Http::fake();
        $this->post('/contact-form', $this->contactPayload());
        $body = json_encode([
            'event' => 'invitee.created',
            'payload' => [
                'email' => 'lead@example.com',
                'uri' => 'https://api.calendly.com/scheduled_events/example/invitees/example',
            ],
        ], JSON_THROW_ON_ERROR);

        $this->postJson('/webhooks/calendly')->assertNotFound();
        config(['lead_automation.calendly_webhook_signing_key' => 'test-key']);
        $this->postJson('/webhooks/calendly')->assertUnauthorized();
        $this->assertDatabaseHas('leads', ['status' => Lead::STATUS_NUEVO]);

        $this->signedWebhook($body, now()->timestamp - 181)->assertUnauthorized();
        $this->signedWebhook($body, now()->timestamp, str_replace('lead@', 'other@', $body))->assertUnauthorized();
        $this->signedWebhook($body, now()->timestamp)->assertOk();
        $this->signedWebhook($body, now()->timestamp)->assertOk();
        $this->assertDatabaseHas('leads', ['status' => Lead::STATUS_MEETING_SCHEDULED]);
        $this->assertSame(1, LeadEvent::where('event_type', LeadEvent::TYPE_MEETING_BOOKED)->count());
        Http::assertNothingSent();
    }

    public function test_admin_panel_requires_an_explicitly_allowed_email(): void
    {
        config(['auth.admin_emails' => ['owner@example.com']]);
        $panel = Filament::getPanel('admin');

        $this->assertFalse((new User(['email' => 'admin@example.com']))->canAccessPanel($panel));
        $this->assertTrue((new User(['email' => 'owner@example.com']))->canAccessPanel($panel));
        $this->seed();
        $this->assertDatabaseEmpty('users');
    }

    private function contactPayload(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Example Lead',
            'email' => 'lead@example.com',
            'project_type' => 'Portfolio',
            'budget_range' => 'medio',
            'urgency' => 'flexible',
        ], $overrides);
    }

    private function signedWebhook(string $body, int $timestamp, ?string $bodyToSend = null)
    {
        $signature = hash_hmac('sha256', $timestamp.'.'.$body, 'test-key');

        return $this->call('POST', '/webhooks/calendly', server: [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_CALENDLY_WEBHOOK_SIGNATURE' => "t={$timestamp},v1={$signature}",
        ], content: $bodyToSend ?? $body);
    }
}
