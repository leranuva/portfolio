<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadContactFormRequest;
use App\Jobs\SyncLeadToEmailProvider;
use App\Mail\ContactMessageReceived;
use App\Mail\HighIntentLeadReceived;
use App\Mail\LeadReceived;
use App\Models\ContactMessage;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\SiteSetting;
use App\Services\LeadEventService;
use App\Services\LeadScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function __invoke(LeadContactFormRequest $request): RedirectResponse
    {
        try {
            return $this->processForm($request);
        } catch (\Throwable $e) {
            Log::error('[ContactForm] 500 error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->to(route('home') . '#contact')
                ->with('contact_error', 'Something went wrong. Please try again or email us directly.')
                ->withInput();
        }
    }

    private function processForm(LeadContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $score = app(LeadScoringService::class)->calculateScore($data);

        $lead = Lead::create([
            ...$data,
            'score' => $score,
            'status' => Lead::STATUS_NUEVO,
            'source' => Lead::SOURCE_CONTACT,
            'utm_source' => $request->input('utm_source') ?? session('utm_source'),
            'utm_medium' => $request->input('utm_medium') ?? session('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign') ?? session('utm_campaign'),
        ]);

        app(LeadEventService::class)->record($lead, LeadEvent::TYPE_LEAD_CREATED, ['score' => $score]);

        $contactMessage = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => "Lead: {$data['project_type']} (Score: {$score})",
            'message' => "Project type: {$data['project_type']}\nBudget: {$data['budget_range']}\nUrgency: {$data['urgency']}\nWhat to automate: " . ($data['what_to_automate'] ?? '') . "\n\nMessage: " . ($data['message'] ?? ''),
        ]);

        $adminEmail = SiteSetting::get('contact_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        }

        $mailable = $score >= 10
            ? new HighIntentLeadReceived($lead)
            : new LeadReceived($lead);
        Mail::to($lead->email)->send($mailable);
        app(LeadEventService::class)->record($lead, LeadEvent::TYPE_EMAIL_SENT, ['type' => $score >= 10 ? 'high_intent' : 'standard']);

        SyncLeadToEmailProvider::dispatch($lead);

        return redirect()->to(route('home') . '#contact')->with('contact_success', true);
    }
}
