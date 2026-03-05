<section id="problem" class="py-24 md:py-32 px-4 bg-white dark:bg-zinc-900/50">
    <div class="max-w-4xl mx-auto text-center"
         x-data="{ visible: false }"
         x-intersect.once="visible = true">
        <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Do you identify?</p>
        <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight mb-8"
            :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
            style="animation-delay: 50ms">
            Are you losing time with repetitive tasks?
        </h2>
        <p class="text-xl md:text-2xl text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed"
           :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
           style="animation-delay: 100ms">
            Does your business run on Excel spreadsheets, paper, or legacy systems?
        </p>
        <p class="text-2xl md:text-3xl font-semibold text-indigo-600 dark:text-indigo-400"
           :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
           style="animation-delay: 150ms">
            → I can help you.
        </p>
        <a href="#contact"
           class="inline-flex items-center gap-2 mt-10 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900"
           :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
           style="animation-delay: 200ms">
            Tell me about your project
            <span>→</span>
        </a>
    </div>
</section>
