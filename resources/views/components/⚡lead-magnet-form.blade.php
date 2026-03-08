<?php

use App\Models\Lead;
use App\Services\LeadEventService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $website = '';

    public bool $success = false;

    public string $resourceSlug = '';

    public function mount(string $resourceSlug = 'auditoria'): void
    {
        $this->resourceSlug = $resourceSlug;
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $lead = Lead::create([
            'name' => explode('@', $this->email)[0],
            'email' => $this->email,
            'project_type' => 'Lead magnet: ' . $this->resourceSlug,
            'what_to_automate' => null,
            'budget_range' => 'medio',
            'urgency' => 'flexible',
            'message' => $this->website ? "Website: {$this->website}" : null,
            'score' => 0,
            'status' => Lead::STATUS_NUEVO,
            'source' => Lead::SOURCE_LEAD_MAGNET,
            'utm_source' => request()->query('utm_source') ?? session('utm_source'),
            'utm_medium' => request()->query('utm_medium') ?? session('utm_medium'),
            'utm_campaign' => request()->query('utm_campaign') ?? session('utm_campaign'),
        ]);

        app(LeadEventService::class)->record($lead, \App\Models\LeadEvent::TYPE_LEAD_CREATED, [
            'resource' => $this->resourceSlug,
        ]);

        // TODO: Send lead magnet email with download link when Mail class exists
        // Mail::to($lead->email)->queue(new LeadMagnetReceived($lead, $this->resourceSlug));

        $this->reset(['email', 'website']);
        $this->success = true;
    }
};
?>

<div>
    @if($success)
        <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/60 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-200 text-center">
            <p class="font-medium">Check your inbox!</p>
            <p class="text-sm mt-1 opacity-90">We've sent you the resource.</p>
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
                <span wire:loading.remove wire:target="submit">Get the resource</span>
                <span wire:loading wire:target="submit">Sending...</span>
            </button>
        </form>
    @endif
</div>
