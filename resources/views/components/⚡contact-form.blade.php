<?php

use App\Jobs\SyncLeadToEmailProvider;
use App\Mail\ContactMessageReceived;
use App\Mail\HighIntentLeadReceived;
use App\Mail\LeadReceived;
use App\Models\ContactMessage;
use App\Models\Lead;
use App\Models\SiteSetting;
use App\Services\LeadEventService;
use App\Services\LeadScoringService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $project_type = '';

    public string $what_to_automate = '';

    public string $budget_range = '';

    public string $urgency = '';

    public string $message = '';

    public bool $success = false;

    public ?string $utm_source = null;

    public ?string $utm_medium = null;

    public ?string $utm_campaign = null;

    public function mount(): void
    {
        $this->utm_source = request()->query('utm_source') ?? session('utm_source');
        $this->utm_medium = request()->query('utm_medium') ?? session('utm_medium');
        $this->utm_campaign = request()->query('utm_campaign') ?? session('utm_campaign');
        if (request()->hasAny(['utm_source', 'utm_medium', 'utm_campaign'])) {
            session([
                'utm_source' => request()->query('utm_source'),
                'utm_medium' => request()->query('utm_medium'),
                'utm_campaign' => request()->query('utm_campaign'),
            ]);
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'project_type' => ['required', 'string', 'max:255'],
            'what_to_automate' => ['nullable', 'string', 'max:1000'],
            'budget_range' => ['required', 'string', 'in:bajo,medio,alto'],
            'urgency' => ['required', 'string', 'in:flexible,pronto,inmediato'],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'project_type.required' => 'Project type is required.',
            'budget_range.required' => 'Please select a budget range.',
            'urgency.required' => 'Please select urgency.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'project_type' => $this->project_type,
            'what_to_automate' => $this->what_to_automate,
            'budget_range' => $this->budget_range,
            'urgency' => $this->urgency,
            'message' => $this->message,
        ];

        $score = app(LeadScoringService::class)->calculateScore($data);

        $lead = Lead::create([
            ...$data,
            'score' => $score,
            'status' => Lead::STATUS_NUEVO,
            'source' => Lead::SOURCE_CONTACT,
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
        ]);

        app(LeadEventService::class)->record($lead, \App\Models\LeadEvent::TYPE_LEAD_CREATED, ['score' => $score]);

        // Also create contact message for admin notification (backward compatibility)
        $contactMessage = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => "Lead: {$this->project_type} (Score: {$score})",
            'message' => "Project type: {$this->project_type}\nBudget: {$this->budget_range}\nUrgency: {$this->urgency}\nWhat to automate: {$this->what_to_automate}\n\nMessage: {$this->message}",
        ]);

        $adminEmail = SiteSetting::get('contact_email');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ContactMessageReceived($contactMessage));
        }

        // Email automático al cliente: hot (score >= 10) → CTA directo Calendly; cold → LeadReceived
        $mailable = $score >= 10
            ? new HighIntentLeadReceived($lead)
            : new LeadReceived($lead);
        Mail::to($lead->email)->queue($mailable);
        app(LeadEventService::class)->record($lead, \App\Models\LeadEvent::TYPE_EMAIL_SENT, ['type' => $score >= 10 ? 'high_intent' : 'standard']);

        SyncLeadToEmailProvider::dispatch($lead);

        $this->reset(['name', 'email', 'project_type', 'what_to_automate', 'budget_range', 'urgency', 'message']);
        $this->success = true;
    }
};
?>

<div>
    @if($success)
        <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/60 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-200 text-center">
            <p class="font-medium">Request received successfully.</p>
            <p class="text-sm mt-1 opacity-90">We'll get back to you soon.</p>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            <div>
                <label for="contact-name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Name</label>
                <input type="text"
                       id="contact-name"
                       wire:model="name"
                       class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('name') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                       placeholder="Your name">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Email</label>
                <input type="email"
                       id="contact-email"
                       wire:model="email"
                       class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                       placeholder="your@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-project-type" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Project type</label>
                <input type="text"
                       id="contact-project-type"
                       wire:model="project_type"
                       placeholder="e.g. Process automation, Custom web system, Freelance retainer"
                       class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('project_type') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror">
                @error('project_type')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-what-automate" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">What do you need to automate?</label>
                <textarea id="contact-what-automate"
                          wire:model="what_to_automate"
                          rows="3"
                          placeholder="Describe the processes or tasks you want to automate"
                          class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 resize-none"></textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="contact-budget" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Estimated budget</label>
                    <select id="contact-budget"
                            wire:model="budget_range"
                            class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('budget_range') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror">
                        <option value="">Select...</option>
                        <option value="bajo">Low</option>
                        <option value="medio">Medium</option>
                        <option value="alto">High</option>
                    </select>
                    @error('budget_range')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact-urgency" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Urgency</label>
                    <select id="contact-urgency"
                            wire:model="urgency"
                            class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('urgency') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror">
                        <option value="">Select...</option>
                        <option value="flexible">Flexible</option>
                        <option value="pronto">Soon</option>
                        <option value="inmediato">Immediate</option>
                    </select>
                    @error('urgency')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="contact-message" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Additional details (optional)</label>
                <textarea id="contact-message"
                          wire:model="message"
                          rows="4"
                          placeholder="Tell me more about your project..."
                          class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 resize-none @error('message') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"></textarea>
                @error('message')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full px-8 py-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900 transition-all duration-300">
                <span wire:loading.remove wire:target="submit">Send request</span>
                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </form>
    @endif
</div>
