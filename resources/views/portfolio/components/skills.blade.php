<section id="skills" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Skills & Technologies</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                Skills & Technologies
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
            @foreach($skillsGrouped ?? [] as $categoryName => $categorySkills)
                <div class="space-y-6"
                     x-data="{ visible: false }"
                     x-intersect.once="visible = true"
                     :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                     :style="'animation-delay: ' + ({{ $loop->index * 80 }}) + 'ms'">
                    <h3 class="font-serif text-lg font-semibold text-zinc-900 dark:text-zinc-100 border-b border-zinc-200 dark:border-zinc-700 pb-2">
                        {{ $categoryName }}
                    </h3>
                    <div class="space-y-8">
                        @foreach($categorySkills as $index => $skill)
                            @php
                                $iconName = $skill->icon ? "heroicon-o-{$skill->icon}" : 'heroicon-o-code-bracket';
                            @endphp
                            <div x-data="{ visible: false, width: 0 }"
                                 x-intersect.once="visible = true; width = {{ $skill->percentage }}"
                                 :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                                 :style="'animation-delay: ' + ({{ $index * 50 }}) + 'ms'">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100 text-sm">{{ $skill->name }}</span>
                                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400 tabular-nums">{{ $skill->percentage }}%</span>
                                </div>
                                <div class="h-2 bg-zinc-200/80 dark:bg-zinc-800/80 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-500 dark:to-indigo-600 rounded-full transition-all duration-1000 ease-out"
                                         :style="'width: ' + width + '%'">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
