<section id="case-studies" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Success stories</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Case studies
            </h2>
        </div>

        <div class="space-y-14 md:space-y-16">
            {{-- Caso 1: BlueDraft --}}
            <div class="p-8 md:p-10 rounded-2xl bg-white dark:bg-zinc-900/50 border border-zinc-200/60 dark:border-zinc-700/40 shadow-lg shadow-zinc-200/50 dark:shadow-zinc-950/50"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center font-bold text-indigo-600 dark:text-indigo-400">1</span>
                    <h3 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-100">BlueDraft</h3>
                </div>
                <div class="space-y-4 text-zinc-600 dark:text-zinc-400">
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Client problem</p>
                        <p>BlueDraft needed a modern, scalable web platform for NYC construction services with automated lead capture, email workflows, and district-specific pages.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Solution delivered</p>
                        <p>Built a responsive, fully scalable website with automated contact flows, multi-district pillar pages, lead scoring, email sequence automation, and an admin panel for internal management, improving lead conversion and operational efficiency.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Technologies</p>
                        <p class="text-sm">Laravel 12, Livewire, Tailwind CSS v4, Filament 5, REST APIs</p>
                    </div>
                </div>
            </div>

            <div class="py-4 md:py-6" aria-hidden="true">
                <div class="h-px bg-gradient-to-r from-transparent via-zinc-300/80 dark:via-zinc-600/60 to-transparent"></div>
            </div>

            {{-- Caso 2: Flat Rate Imports — Smart Import Quotation System --}}
            <div class="p-8 md:p-10 rounded-2xl bg-white dark:bg-zinc-900/50 border border-zinc-200/60 dark:border-zinc-700/40 shadow-lg shadow-zinc-200/50 dark:shadow-zinc-950/50"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                 style="animation-delay: 100ms">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center font-bold text-indigo-600 dark:text-indigo-400">2</span>
                    <h3 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Flat Rate Imports — Smart Import Quotation System</h3>
                </div>
                <div class="space-y-4 text-zinc-600 dark:text-zinc-400">
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Client problem</p>
                        <p>Customers in Ecuador needed a simple way to calculate import costs, select shipping methods, and track U.S. purchases, while admins required full control over products, taxes, and content.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Solution delivered</p>
                        <p>Developed a Laravel 12 web application with Tailwind CSS & Alpine.js featuring a smart import quotation system, dynamic admin panel, theme management, shipment tracking, PDF export, and responsive Glassmorphism UI.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Technologies</p>
                        <p class="text-sm">Laravel 12, PHP 8.2+, MySQL, Tailwind CSS 3.1, Alpine.js 3.4, Vite, Blade components, jsPDF.</p>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Impact</p>
                        <p>Streamlined import workflow, improved user experience, and provided admins with a scalable, secure management system.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
