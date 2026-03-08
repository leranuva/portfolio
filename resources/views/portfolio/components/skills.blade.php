<section id="skills" class="py-24 md:py-32 px-4 bg-[#fafaf9] dark:bg-[#0c0c0f]">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16 md:mb-20"
             x-data="{ visible: false }"
             x-intersect.once="visible = true">
            <p class="section-label mb-4" :class="visible ? 'animate-fade-in-up' : 'opacity-0'">Skills & Technologies</p>
            <h2 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight"
                :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                style="animation-delay: 50ms">
                Tech stack & tools
            </h2>
            <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-400"
               :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
               style="animation-delay: 100ms">
                Technologies I work with to build modern, scalable applications.
            </p>
        </div>

        <div class="space-y-14 md:space-y-20">
            @foreach($skillsGrouped ?? [] as $categoryName => $categorySkills)
                <div class="flex flex-col md:flex-row md:items-start md:gap-8"
                     x-data="{ visible: false }"
                     x-intersect.once="visible = true"
                     :class="visible ? 'animate-fade-in-up' : 'opacity-0'"
                     :style="'animation-delay: ' + ({{ $loop->index * 50 }}) + 'ms'">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-3 md:mb-0 md:w-40 md:shrink-0 md:pt-1">
                        {{ $categoryName }}
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categorySkills as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors duration-200">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
