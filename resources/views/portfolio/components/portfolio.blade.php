<section id="portfolio" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]"
         x-data="{ activeFilter: 'all' }">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Portfolio</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Featured projects
            </h2>
        </div>

        @if(($categories ?? collect())->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2 mb-12"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true">
                <button type="button"
                        @click="activeFilter = 'all'"
                        :class="activeFilter === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'bg-zinc-200/80 dark:bg-zinc-800/80 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-300 dark:hover:bg-zinc-700'"
                        class="px-5 py-2.5 rounded-xl font-medium transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900">
                    All
                </button>
                @foreach($categories as $category)
                    <button type="button"
                            @click="activeFilter = '{{ $category->slug }}'"
                            :class="activeFilter === '{{ $category->slug }}' ? 'bg-indigo-600 text-white shadow-md' : 'bg-zinc-200/80 dark:bg-zinc-800/80 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-300 dark:hover:bg-zinc-700'"
                            class="px-5 py-2.5 rounded-xl font-medium transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-900">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects ?? [] as $index => $project)
                <div class="group rounded-2xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-500 cursor-pointer"
                     x-data="{ visible: false }"
                     x-intersect.once="visible = true"
                     x-show="activeFilter === 'all' || '{{ $project->category?->slug ?? '' }}' === activeFilter"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                     :style="'animation-delay: ' + ({{ $index * 80 }}) + 'ms'"
                     @click="window.location.href = '{{ url('/projects/' . $project->slug) }}'">
                    <div class="relative overflow-hidden aspect-[4/3]">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}"
                                 alt="{{ $project->title }}"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-zinc-200 to-zinc-300 dark:from-zinc-700 dark:to-zinc-800 flex items-center justify-center">
                                <span class="text-5xl text-zinc-400 dark:text-zinc-500">📁</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-500 bg-zinc-900/40">
                            <span class="text-white font-medium">View project</span>
                        </div>
                    </div>
                    <div class="p-6 bg-white dark:bg-zinc-900/50">
                        <h3 class="font-serif text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                            {{ $project->title }}
                        </h3>
                        @if($project->category)
                            <p class="text-sm text-zinc-500 dark:text-zinc-500 mt-1">
                                {{ $project->category->name }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
