<?php

namespace App\Services;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ContactSubmissionService
{
    public function __construct(
        private readonly LeadScoringService $scoring,
        private readonly LeadEventService $events,
    ) {}

    public function submit(array $data): void
    {
        $payload = Arr::except($data, [...PublicSubmission::TRACKING_FIELDS, 'company_website', 'cf-turnstile-response']);
        $message = PublicSubmission::once('lead', $payload, fn () => $this->store($payload, PublicSubmission::tracking($data)));

        if ($message && config('contact.notify_admin')) {
            $this->notifyAdmin($message);
        }
    }

    private function store(array $data, array $tracking): ContactMessage
    {
        return DB::transaction(function () use ($data, $tracking): ContactMessage {
            $score = $this->scoring->calculateScore($data);
            $lead = Lead::create([
                ...$data,
                ...$tracking,
                'score' => $score,
                'status' => Lead::STATUS_NUEVO,
                'source' => Lead::SOURCE_CONTACT,
            ]);

            $this->events->record($lead, LeadEvent::TYPE_LEAD_CREATED, ['score' => $score]);

            return ContactMessage::create([
                'name' => $lead->name,
                'email' => $lead->email,
                'subject' => Str::substr("Lead: {$lead->project_type} (Score: {$score})", 0, 255),
                'message' => "Project type: {$lead->project_type}\nBudget: {$lead->budget_range}\nUrgency: {$lead->urgency}"
                    ."\nWhat to automate: {$lead->what_to_automate}\n\nMessage: {$lead->message}",
            ]);
        });
    }

    private function notifyAdmin(ContactMessage $message): void
    {
        try {
            $email = SiteSetting::get('contact_email');
            if ($email) {
                Mail::to($email)->send(new ContactMessageReceived($message));
            }
        } catch (Throwable $exception) {
            Log::warning('Contact notification failed', [
                'contact_message_id' => $message->id,
                'exception_type' => $exception::class,
            ]);
        }
    }
}
