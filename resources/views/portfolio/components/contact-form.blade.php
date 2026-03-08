@php
if (request()->hasAny(['utm_source', 'utm_medium', 'utm_campaign'])) {
    session([
        'utm_source' => request()->query('utm_source'),
        'utm_medium' => request()->query('utm_medium'),
        'utm_campaign' => request()->query('utm_campaign'),
    ]);
}
@endphp
@if(session('contact_success'))
    <div class="p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200/60 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-200 text-center">
        <p class="font-medium">Request received successfully.</p>
        <p class="text-sm mt-1 opacity-90">We'll get back to you soon.</p>
    </div>
@elseif(session('contact_error'))
    <div class="p-6 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200/60 dark:border-red-800/40 text-red-800 dark:text-red-200 text-center mb-6">
        <p class="font-medium">{{ session('contact_error') }}</p>
    </div>
@endif

@if(!session('contact_success'))
    <form method="POST" action="{{ route('contact.form.submit') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="utm_source" value="{{ request()->query('utm_source') ?? session('utm_source') }}">
        <input type="hidden" name="utm_medium" value="{{ request()->query('utm_medium') ?? session('utm_medium') }}">
        <input type="hidden" name="utm_campaign" value="{{ request()->query('utm_campaign') ?? session('utm_campaign') }}">

        <div>
            <label for="contact-name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Name</label>
            <input type="text" name="name" id="contact-name" value="{{ old('name') }}"
                   class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('name') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                   placeholder="Your name" required>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Email</label>
            <input type="email" name="email" id="contact-email" value="{{ old('email') }}"
                   class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                   placeholder="your@email.com" required>
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-project-type" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Project type</label>
            <input type="text" name="project_type" id="contact-project-type" value="{{ old('project_type') }}"
                   placeholder="e.g. Process automation, Custom web system, Freelance retainer"
                   class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('project_type') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror" required>
            @error('project_type')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="contact-what-automate" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">What do you need to automate?</label>
            <textarea name="what_to_automate" id="contact-what-automate" rows="3"
                      placeholder="Describe the processes or tasks you want to automate"
                      class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 resize-none">{{ old('what_to_automate') }}</textarea>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="contact-budget" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Estimated budget</label>
                <select name="budget_range" id="contact-budget"
                        class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('budget_range') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror" required>
                    <option value="">Select...</option>
                    <option value="bajo" {{ old('budget_range') === 'bajo' ? 'selected' : '' }}>Low</option>
                    <option value="medio" {{ old('budget_range') === 'medio' ? 'selected' : '' }}>Medium</option>
                    <option value="alto" {{ old('budget_range') === 'alto' ? 'selected' : '' }}>High</option>
                </select>
                @error('budget_range')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact-urgency" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Urgency</label>
                <select name="urgency" id="contact-urgency"
                        class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 @error('urgency') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror" required>
                    <option value="">Select...</option>
                    <option value="flexible" {{ old('urgency') === 'flexible' ? 'selected' : '' }}>Flexible</option>
                    <option value="pronto" {{ old('urgency') === 'pronto' ? 'selected' : '' }}>Soon</option>
                    <option value="inmediato" {{ old('urgency') === 'inmediato' ? 'selected' : '' }}>Immediate</option>
                </select>
                @error('urgency')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="contact-message" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Additional details (optional)</label>
            <textarea name="message" id="contact-message" rows="4"
                      placeholder="Tell me more about your project..."
                      class="w-full px-5 py-3.5 rounded-xl border border-zinc-300/80 dark:border-zinc-600/80 bg-white dark:bg-zinc-900/50 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 resize-none @error('message') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full px-8 py-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900 transition-all duration-300">
            Send request
        </button>
    </form>
@endif
