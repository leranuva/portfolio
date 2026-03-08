<section id="contact" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Get in touch</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Tell me about your project
            </h2>
            <p class="mt-4 text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto"
               :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
               style="animation-delay: 50ms">
                Fill out the form below and I'll get back to you as soon as possible.
            </p>
        </div>

        @include('portfolio.components.contact-form')

        <div class="mt-16 flex flex-wrap justify-center gap-8"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            @if($email ?? null)
                <a href="mailto:{{ $email }}"
                   class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300"
                   :class="visible ? 'animate-fade-in-up' : 'opacity-0'">
                    <span class="w-10 h-10 rounded-xl bg-zinc-200/80 dark:bg-zinc-700/50 flex items-center justify-center text-lg">✉️</span>
                    <span class="font-medium">{{ $email }}</span>
                </a>
            @endif
            @if($github ?? null)
                <a href="{{ $github }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300"
                   :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                   style="animation-delay: 50ms">
                    <span class="w-10 h-10 rounded-xl bg-zinc-200/80 dark:bg-zinc-700/50 flex items-center justify-center font-semibold text-sm">GH</span>
                    <span class="font-medium">GitHub</span>
                </a>
            @endif
            @if($cvUrl ?? null)
                <a href="{{ $cvUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300"
                   :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                   style="animation-delay: 100ms">
                    <span class="w-10 h-10 rounded-xl bg-zinc-200/80 dark:bg-zinc-700/50 flex items-center justify-center text-lg">📄</span>
                    <span class="font-medium">Download CV</span>
                </a>
            @endif
        </div>
    </div>
</section>
