<?php

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Services\LeadEventService;
use App\Services\PublicSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $website = '';

    public bool $success = false;

    #[Locked]
    public string $resourceSlug = '';

    public function mount(string $resourceSlug = 'auditoria'): void
    {
        $this->resourceSlug = $resourceSlug;
    }

    protected function rules(): array
    {
        return [
            'email' => PublicSubmission::emailRules(),
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function submit(): void
    {
        if (! RateLimiter::attempt('lead-magnet:ip:'.request()->ip(), 5, fn () => true)
            || ! RateLimiter::attempt('lead-magnet:global', 30, fn () => true)) {
            $this->addError('email', 'Too many attempts. Please try again later.');

            return;
        }

        $data = $this->validate();
        if (! RateLimiter::attempt('lead-magnet:recipient:'.PublicSubmission::recipientKey($data['email']), 3, fn () => true, 3600)) {
            $this->addError('email', 'Too many attempts. Please try again later.');

            return;
        }

        PublicSubmission::once('lead-magnet', [...$data, 'resource' => $this->resourceSlug], function () use ($data): void {
            DB::transaction(function () use ($data): void {
                $lead = Lead::create([
                    'name' => explode('@', $data['email'])[0],
                    'email' => $data['email'],
                    'project_type' => 'Lead magnet: '.$this->resourceSlug,
                    'budget_range' => 'medio',
                    'urgency' => 'flexible',
                    'message' => filled($data['website']) ? 'Website: '.$data['website'] : null,
                    'score' => 0,
                    'status' => Lead::STATUS_NUEVO,
                    'source' => Lead::SOURCE_LEAD_MAGNET,
                    ...PublicSubmission::tracking(session()->only(PublicSubmission::TRACKING_FIELDS)),
                ]);

                app(LeadEventService::class)->record($lead, LeadEvent::TYPE_LEAD_CREATED, [
                    'resource' => $this->resourceSlug,
                ]);
            });
        });

        $this->reset(['email', 'website']);
        $this->success = true;
    }
};
?>

<div>
    @if($success)
        <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/60 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-200 text-center">
            <p class="font-medium">Request received.</p>
        </div>
    @else
        <form wire:submit="submit" class="space-y-4">
            <div>
                <label for="lm-email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Email</label>
                <input type="email"
                       id="lm-email"
                       wire:model="email"
                       class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 @enderror"
                       placeholder="your@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="lm-website" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Website (optional)</label>
                <input type="url"
                       id="lm-website"
                       wire:model="website"
                       placeholder="https://yoursite.com"
                       class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200">
            </div>
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full px-8 py-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] disabled:opacity-60 text-white font-semibold rounded-xl transition-colors">
                <span wire:loading.remove wire:target="submit">Request the resource</span>
                <span wire:loading wire:target="submit">Sending...</span>
            </button>
        </form>
    @endif
</div>
