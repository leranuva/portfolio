<section id="blog" class="py-24 md:py-32 px-4 bg-white dark:bg-zinc-900/50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Blog</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Latest articles
            </h2>
        </div>

        @if(($blogPosts ?? collect())->isNotEmpty())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogPosts as $index => $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="block group">
                    <article class="rounded-2xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition-all duration-500"
                             x-data="{ visible: false }"
                             x-intersect.once="visible = true"
                             :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                             :style="'animation-delay: ' + ({{ $index * 100 }}) + 'ms'">
                        <div class="aspect-[16/10] overflow-hidden">
                            @if($post->image)
                                <img src="{{ $post->image_url }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center">
                                    <span class="text-4xl text-zinc-400 dark:text-zinc-500">📝</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-serif text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">
                                    {{ $post->excerpt }}
                                </p>
                            @endif
                            @if($post->published_at)
                                <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-3 font-medium">
                                    {{ $post->published_at->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </article>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-600"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true">
                <p class="text-zinc-500 dark:text-zinc-500 font-medium" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">
                    Interesting articles coming soon.
                </p>
            </div>
        @endif
    </div>
</section>
