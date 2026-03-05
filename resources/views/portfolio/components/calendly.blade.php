@if($calendlyUrl ?? null)
<section id="calendly" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Book a call</p>
            <h2 class="font-serif text-3xl md:text-4xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight"
                :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                style="animation-delay: 50ms">
                Schedule a free 30-minute consultation
            </h2>
            <p class="mt-4 text-zinc-600 dark:text-zinc-400"
               :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
               style="animation-delay: 100ms">
                Tell me about your project and let's see how I can help.
            </p>
        </div>

        <div class="rounded-2xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/40 shadow-xl bg-white dark:bg-zinc-900/50"
             x-data="{ visible: false }"
             x-intersect.once="visible = true"
             :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
             style="animation-delay: 150ms">
            <iframe src="{{ rtrim($calendlyUrl, '/') }}?embed=true"
                    class="w-full min-h-[700px] border-0"
                    allowfullscreen
                    allow="camera; microphone; autoplay; encrypted-media"></iframe>
        </div>
    </div>
</section>
@endif
