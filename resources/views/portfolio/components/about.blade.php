<section id="about" class="py-24 md:py-32 px-4 bg-white dark:bg-zinc-900/50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">About</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Experience and dedication
            </h2>
        </div>

        @if(($counters ?? collect())->isNotEmpty())
            <div class="grid md:grid-cols-3 gap-6 mb-16">
                @foreach($counters as $index => $counter)
                    <div class="group p-8 rounded-2xl bg-zinc-50/80 dark:bg-zinc-800/30 border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/50 hover:shadow-lg hover:shadow-indigo-500/5 transition-all duration-500"
                         x-data="{
                             visible: false,
                             displayValue: 0,
                             target: {{ $counter->value }},
                             suffix: '{{ $counter->suffix }}',
                             duration: 1500,
                             startCountUp() {
                                 const startTime = performance.now();
                                 const step = (timestamp) => {
                                     const elapsed = timestamp - startTime;
                                     const progress = Math.min(elapsed / this.duration, 1);
                                     this.displayValue = Math.floor(progress * this.target);
                                     if (progress < 1) requestAnimationFrame(step);
                                 };
                                 requestAnimationFrame(step);
                             }
                         }"
                         x-intersect.once="visible = true; startCountUp()"
                         :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                         :style="'animation-delay: ' + ({{ $index * 100 }}) + 'ms'">
                        <p class="text-4xl md:text-5xl font-bold text-indigo-600 dark:text-indigo-400 tabular-nums">
                            <span x-text="displayValue + suffix"></span>
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400 mt-2 font-medium">
                            {{ $counter->label }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        @if($text ?? null)
            <div class="max-w-3xl mx-auto text-center"
                 x-data="{ visible: false }"
                 x-intersect.once="visible = true">
                <div class="prose prose-lg prose-zinc dark:prose-invert max-w-none"
                     :class="visible ? 'animate-fade-in-up' : 'opacity-0'">
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {!! nl2br(e($text)) !!}
                    </p>
                </div>
            </div>
        @endif
    </div>
</section>
