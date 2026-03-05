<section id="offers" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Services</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Clear offers
            </h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Plan 1 --}}
            <div class="group flex flex-col p-8 rounded-2xl bg-zinc-50/50 dark:bg-zinc-800/20 border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition-all duration-500"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'">
                <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    {!! svg('heroicon-o-cog-6-tooth', 'w-7 h-7 text-indigo-600 dark:text-indigo-400')->toHtml() !!}
                </div>
                <h3 class="font-serif text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Process automation</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">Streamline your workflows with custom development and API integrations.</p>
                <ul class="space-y-3 text-zinc-600 dark:text-zinc-400 mb-8 flex-grow">
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Process audit</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Custom development</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>API and tool integration</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Delivery + support</span>
                    </li>
                </ul>
                <a href="#contact" class="inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300">
                    Request quote
                </a>
            </div>

            {{-- Plan 2 (destacado) --}}
            <div class="group flex flex-col p-8 rounded-2xl bg-zinc-50/50 dark:bg-zinc-800/20 border-2 border-indigo-300 dark:border-indigo-600/60 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-500 relative"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                 style="animation-delay: 100ms">
                <span class="absolute top-4 right-4 px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white shadow-md">Most popular</span>
                <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    {!! svg('heroicon-o-squares-2x2', 'w-7 h-7 text-indigo-600 dark:text-indigo-400')->toHtml() !!}
                </div>
                <h3 class="font-serif text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Custom web system</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">Full control with admin dashboard, users and permissions.</p>
                <ul class="space-y-3 text-zinc-600 dark:text-zinc-400 mb-8 flex-grow">
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Admin dashboard</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Users and permissions</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Internal automation</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Deployment and maintenance</span>
                    </li>
                </ul>
                <a href="#contact" class="inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300">
                    Request quote
                </a>
            </div>

            {{-- Plan 3 --}}
            <div class="group flex flex-col p-8 rounded-2xl bg-zinc-50/50 dark:bg-zinc-800/20 border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition-all duration-500"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true"
                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                 style="animation-delay: 200ms">
                <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    {!! svg('heroicon-o-calendar-days', 'w-7 h-7 text-indigo-600 dark:text-indigo-400')->toHtml() !!}
                </div>
                <h3 class="font-serif text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Flexible freelance</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">Ongoing development and support with a monthly retainer.</p>
                <ul class="space-y-3 text-zinc-600 dark:text-zinc-400 mb-8 flex-grow">
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Ongoing development</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Optimization and support</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-500">✔</span>
                        <span>Monthly retainer</span>
                    </li>
                </ul>
                <a href="#contact" class="inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-300">
                    Request quote
                </a>
            </div>
        </div>
    </div>
</section>
