@extends('layouts.portfolio')

@section('metaTitle')
{{ $post->title }} — {{ config('app.name') }}
@endsection

@section('metaDescription')
{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 160) }}
@endsection

@if($post->image)
@section('ogImage')
    <meta property="og:image" content="{{ $post->image_url }}">
@endsection
@endif

@section('content')
    <article class="py-24 md:py-32 px-4">
        <div class="max-w-3xl mx-auto">
            <a href="{{ url('/#blog') }}" class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-8">
                ← Back to blog
            </a>

            <header class="mb-12">
                @if($post->published_at)
                    <p class="text-sm text-zinc-500 dark:text-zinc-500 mb-4">
                        {{ $post->published_at->translatedFormat('F j, Y') }}
                    </p>
                @endif
                <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                    {{ $post->title }}
                </h1>
                @if($post->excerpt)
                    <p class="mt-4 text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $post->excerpt }}
                    </p>
                @endif
            </header>

            @if($post->image)
                <div class="rounded-2xl overflow-hidden mb-12 shadow-lg">
                    <img src="{{ $post->image_url }}"
                         alt="{{ $post->title }}"
                         class="w-full h-auto object-cover"
                         loading="lazy">
                </div>
            @endif

            <div class="blog-content">
                {!! \App\Helpers\MarkdownHelper::toHtml($post->content ?? '') !!}
            </div>

            @if(($relatedPosts ?? collect())->isNotEmpty())
                <section class="mt-16 pt-12 border-t border-zinc-200 dark:border-zinc-700">
                    <h2 class="font-serif text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-6">Related articles</h2>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug) }}"
                               class="block p-4 rounded-xl border border-zinc-200/60 dark:border-zinc-700/40 hover:border-indigo-300 dark:hover:border-indigo-600/40 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-all">
                                <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $related->title }}</h3>
                                @if($related->excerpt)
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $related->excerpt }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <footer class="mt-16 pt-8 border-t border-zinc-200 dark:border-zinc-700">
                <a href="{{ url('/#contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                    Get in touch
                    <span>→</span>
                </a>
            </footer>
        </div>
    </article>
@endsection
