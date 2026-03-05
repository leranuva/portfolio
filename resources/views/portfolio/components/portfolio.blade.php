<section id="portfolio" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]"
         x-data="{ activeFilter: 'all', openProject: null }">
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
                     @click="openProject = {
                         title: '{{ addslashes($project->title) }}',
                         description: {{ Js::from($project->description) }},
                         image: {{ Js::from($project->image ? Storage::url($project->image) : null) }},
                         url: {{ Js::from($project->url) }},
                         video_url: {{ Js::from($project->video_url) }},
                         category: {{ Js::from($project->category?->name) }}
                     }">
                    <div class="relative overflow-hidden aspect-[4/3]">
                        @if($project->image)
                            <img src="{{ Storage::url($project->image) }}"
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
                            <span class="text-white font-medium">View details</span>
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

        {{-- Modal detalle proyecto --}}
        <div x-show="openProject"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/80 backdrop-blur-sm"
             @click.self="openProject = null"
             @keydown.escape.window="openProject = null">
            <template x-if="openProject">
                <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white dark:bg-zinc-900 shadow-2xl"
                     @click.stop>
                    <button type="button"
                            @click="openProject = null"
                            class="absolute top-4 right-4 p-2 rounded-lg text-zinc-500 hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-colors z-10"
                            aria-label="Close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="p-6 md:p-8">
                        <h3 class="font-serif text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2" x-text="openProject.title"></h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-500 mb-6" x-text="openProject.category || ''"></p>

                        <template x-if="openProject.video_url">
                            <div class="aspect-video rounded-xl overflow-hidden mb-6 bg-zinc-200 dark:bg-zinc-800"
                                 x-data="{
                                     embedUrl: (() => {
                                         const u = openProject.video_url;
                                         if (u.includes('vimeo.com')) {
                                             const m = u.match(/vimeo\.com\/(\d+)/);
                                             return m ? 'https://player.vimeo.com/video/' + m[1] : u;
                                         }
                                         return u.replace('youtube.com/watch?v=', 'youtube.com/embed/').replace('youtu.be/', 'youtube.com/embed/').split('&')[0];
                                     })()
                                 }">
                                <iframe :src="embedUrl"
                                        class="w-full h-full"
                                        allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                            </div>
                        </template>
                        <template x-if="!openProject.video_url && openProject.image">
                            <img :src="openProject.image"
                                 :alt="openProject.title"
                                 class="w-full rounded-xl mb-6 object-cover">
                        </template>
                        <template x-if="openProject.description">
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6 whitespace-pre-line" x-text="openProject.description"></p>
                        </template>
                        <template x-if="openProject.url">
                            <a :href="openProject.url"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="group inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 transition-all duration-300">
                                View project
                                <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>
