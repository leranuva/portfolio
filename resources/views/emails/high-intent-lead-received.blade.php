<x-mail::message>
# You're ready to automate — let's talk, {{ $lead->name }}!

Based on what you shared, your project sounds like a great fit. I'd love to jump on a quick call to discuss how we can make it happen.

**Your project:** {{ $lead->project_type }}

Book a free 30-minute strategy call now — I have slots available this week:

<x-mail::button :url="$calendlyUrl ?? url('/#calendly')">
Book your free call now
</x-mail::button>

I'll also follow up by email, but the fastest way to get started is to grab a slot above.

Best regards,<br>
{{ $senderName ?? config('app.name') }}
</x-mail::message>
