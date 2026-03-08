<x-mail::message>
# Thanks for reaching out, {{ $lead->name }}!

I've received your request and will get back to you as soon as possible — usually within 24–48 hours.

**Your project:** {{ $lead->project_type }}

In the meantime, if you'd like to schedule a free 30-minute strategy call to discuss your automation needs, you can book a slot directly:

<x-mail::button :url="$calendlyUrl ?? url('/#calendly')">
Schedule a free call
</x-mail::button>

Best regards,<br>
{{ $senderName ?? config('app.name') }}
</x-mail::message>
