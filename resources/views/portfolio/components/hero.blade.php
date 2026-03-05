<section id="home" class="relative min-h-screen flex items-center justify-center px-4 pt-24 pb-20 overflow-hidden">
    <div class="absolute inset-0 gradient-mesh pointer-events-none"></div>
    <div class="absolute inset-0 bg-[#fafaf9] dark:bg-[#0c0c0f] -z-10"></div>

    <div class="relative max-w-4xl mx-auto text-center"
         x-data="{ visible: false }"
         x-intersect.once="visible = true">
        @if($image ?? null)
            <div class="mb-8"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                 style="animation-delay: 0ms">
                <img src="{{ $image }}"
                     alt="{{ $name }}"
                     width="176"
                     height="176"
                     class="w-36 h-36 md:w-44 md:h-44 mx-auto rounded-2xl object-cover shadow-2xl shadow-indigo-500/10 dark:shadow-indigo-500/5 ring-1 ring-zinc-200/50 dark:ring-zinc-700/50"
                     fetchpriority="high">
            </div>
        @endif
        <p class="section-label mb-4"
           :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
           style="animation-delay: 100ms">
            Hi, I'm
        </p>
        <h1 class="font-serif text-4xl md:text-5xl lg:text-7xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4 tracking-tight leading-tight"
            :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
            style="animation-delay: 150ms">
            {{ $name }}
        </h1>
        <p class="text-xl md:text-2xl font-medium text-indigo-600 dark:text-indigo-400 mb-4"
           :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
           style="animation-delay: 200ms">
            {{ $title }}
        </p>
        @if($subtitle ?? null)
            <p class="text-lg md:text-xl text-zinc-600 dark:text-zinc-400 mb-12 max-w-2xl mx-auto leading-relaxed"
               :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
               style="animation-delay: 250ms">
                {{ $subtitle }}
            </p>
        @endif
        <div class="flex flex-wrap gap-4 justify-center"
             :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
             style="animation-delay: 350ms">
            <a href="#contact"
               class="group inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900 transition-all duration-300">
                Contact
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
            @if($cvUrl ?? null)
                <a href="{{ $cvUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-8 py-4 border-2 border-zinc-300 dark:border-zinc-600 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 active:scale-[0.98] font-semibold rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900 transition-all duration-300">
                    View CV
                </a>
            @endif
        </div>
    </div>
</section>
