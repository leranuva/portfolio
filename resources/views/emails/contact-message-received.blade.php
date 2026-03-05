<x-mail::message>
# New contact message

You have received a new message from the portfolio contact form.

**Name:** {{ $message->name }}

**Email:** {{ $message->email }}

**Subject:** {{ $message->subject ?: '—' }}

**Message:**

{{ $message->message }}

<x-mail::button :url="url('/admin/contact-messages')">
View messages in panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
