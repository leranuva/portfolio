<section id="services" class="py-24 md:py-32 px-4 bg-white dark:bg-zinc-900/50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Services</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                How I can help you
            </h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services ?? [] as $index => $service)
                <div class="group p-8 rounded-2xl bg-zinc-50/50 dark:bg-zinc-800/20 border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:shadow-xl hover:shadow-indigo-500/5 hover:-translate-y-1 transition-all duration-500"
                     x-data="{ visible: false }"
                     x-intersect.once="visible = true"
                     :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                     :style="'animation-delay: ' + ({{ $index * 100 }}) + 'ms'">
                    @php
                        $iconName = $service->icon ? "heroicon-o-{$service->icon}" : 'heroicon-o-briefcase';
                    @endphp
                    <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        {!! svg($iconName, 'w-7 h-7 text-indigo-600 dark:text-indigo-400')->toHtml() !!}
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                        {{ $service->title }}
                    </h3>
                    @if($service->description)
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            {{ $service->description }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
