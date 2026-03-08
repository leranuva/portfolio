@php
    $content = match($step) {
        1 => [
            'title' => 'Hi ' . $lead->name . ', just checking in',
            'body' => "I wanted to follow up on your interest in **{$lead->project_type}**. Have you had a chance to think about your automation needs?\n\nIf you have any questions or would like to schedule a quick call to discuss your project, I'm here to help.",
        ],
        2 => [
            'title' => '3 signs your business needs automation',
            'body' => "Hi {$lead->name},\n\nMany businesses wait too long before automating. Here are 3 signs it might be time:\n\n1. **You're spending hours on repetitive tasks** — Data entry, reports, manual processes that could be automated.\n2. **Errors are costing you** — Human mistakes in spreadsheets or manual workflows.\n3. **You can't scale** — Your current process doesn't grow with your business.\n\nIf any of these sound familiar, let's talk. I offer a free 30-minute strategy call to explore your options.",
        ],
        3 => [
            'title' => 'One last thing...',
            'body' => "Hi {$lead->name},\n\nI don't want to overwhelm your inbox. This will be my last follow-up.\n\nIf you're still interested in discussing **{$lead->project_type}** or automation in general, I'd love to connect. Book a free 30-minute call at your convenience — no pressure, just a friendly chat about your needs.",
        ],
        default => [
            'title' => 'Follow-up',
            'body' => "Hi {$lead->name}, just following up on your recent inquiry.",
        ],
    };
@endphp
<x-mail::message>
# {{ $content['title'] }}

{{ $content['body'] }}

<x-mail::button :url="$calendlyUrl ?? url('/#calendly')">
Schedule a free 30-min call
</x-mail::button>

Best regards,<br>
{{ $senderName ?? config('app.name') }}
</x-mail::message>
