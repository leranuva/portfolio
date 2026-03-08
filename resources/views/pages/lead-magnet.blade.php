@extends('layouts.portfolio')

@section('metaTitle')
Free Automation Audit Checklist — {{ config('app.name') }}
@endsection

@section('metaDescription')
Get our free automation audit checklist to identify processes you can automate and save hours every week.
@endsection

@section('content')
    <div class="pt-16 md:pt-20 py-24 md:py-32 px-4">
        <div class="max-w-xl mx-auto text-center">
            <h1 class="font-serif text-3xl md:text-4xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight mb-4">
                Free Automation Audit Checklist
            </h1>
            <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-8">
                Identify the processes you can automate and save hours every week. Get our checklist delivered to your inbox.
            </p>
            <div class="bg-white dark:bg-zinc-900/50 rounded-2xl p-8 shadow-lg border border-zinc-200/60 dark:border-zinc-700/40">
                @livewire('lead-magnet-form', ['resourceSlug' => 'auditoria'])
            </div>
            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-500">
                No spam. Unsubscribe anytime.
            </p>
        </div>
    </div>
@endsection
