<nav class="fixed top-0 left-0 right-0 z-50 bg-[#fafaf9]/80 dark:bg-[#0c0c0f]/80 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 transition-all duration-500"
     x-data="{ mobileOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <a href="{{ url('/') }}#home"
               class="font-serif text-xl md:text-2xl font-semibold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300">
                {{ $heroName ?? config('app.name') }}
            </a>

            <div class="hidden md:flex items-center gap-1">
                @foreach ($navLinks ?? [] as $href => $label)
                    <a href="{{ $href }}"
                       class="relative px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors duration-300 after:absolute after:bottom-0 after:left-4 after:right-4 after:h-px after:bg-indigo-500 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300 after:origin-center">
                        {{ $label }}
                    </a>
                @endforeach

                <button type="button"
                        class="ml-2 p-2.5 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/80 dark:hover:bg-zinc-800/80 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-300"
                        aria-label="Toggle dark mode"
                        @click="$store.theme.toggle()">
                    <span x-show="!$store.theme.dark">@include('portfolio.components.icons.sun')</span>
                    <span x-show="$store.theme.dark" x-cloak>@include('portfolio.components.icons.moon')</span>
                </button>
            </div>

            <button type="button"
                    class="md:hidden p-2.5 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/80 dark:hover:bg-zinc-800/80 transition-colors"
                    aria-label="Toggle menu"
                    @click="mobileOpen = !mobileOpen">
                <span x-show="!mobileOpen">@include('portfolio.components.icons.menu')</span>
                <span x-show="mobileOpen" x-cloak>@include('portfolio.components.icons.close')</span>
            </button>
        </div>

        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden py-4 border-t border-zinc-200/50 dark:border-zinc-800/50">
            <div class="flex flex-col gap-1">
                @foreach ($navLinks ?? [] as $href => $label)
                    <a href="{{ $href }}"
                       class="px-4 py-3 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors"
                       @click="mobileOpen = false">
                        {{ $label }}
                    </a>
                @endforeach
                <button type="button"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors text-left"
                        aria-label="Toggle dark mode"
                        @click="$store.theme.toggle()">
                    <span x-show="!$store.theme.dark">@include('portfolio.components.icons.sun')</span>
                    <span x-show="$store.theme.dark" x-cloak>@include('portfolio.components.icons.moon')</span>
                    <span x-text="$store.theme.dark ? 'Dark mode' : 'Light mode'"></span>
                </button>
            </div>
        </div>
    </div>
</nav>
