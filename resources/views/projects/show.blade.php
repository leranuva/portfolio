@extends('layouts.portfolio')

@section('metaTitle')
{{ $project->title }} — {{ config('app.name') }}
@endsection

@section('metaDescription')
{{ \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 160) }}
@endsection

@if($project->image)
@section('ogImage')
    <meta property="og:image" content="{{ asset('storage/' . $project->image) }}">
@endsection
@endif

@section('content')
    <article class="pt-16 md:pt-20 py-24 md:py-32 px-4">
        <div class="max-w-4xl mx-auto">
            <a href="{{ url('/#portfolio') }}" class="inline-flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline mb-8">
                ← Back to portfolio
            </a>

            <header class="mb-12">
                @if($project->category)
                    <p class="text-sm text-zinc-500 dark:text-zinc-500 mb-4">
                        {{ $project->category->name }}
                    </p>
                @endif
                <h1 class="font-serif text-3xl md:text-4xl lg:text-5xl font-semibold text-zinc-900 dark:text-zinc-100 tracking-tight">
                    {{ $project->title }}
                </h1>
            </header>

            @if($project->video_url)
                <div class="aspect-video rounded-2xl overflow-hidden mb-12 bg-zinc-200 dark:bg-zinc-800 shadow-lg"
                     x-data="{
                         embedUrl: (() => {
                             const u = {{ Js::from($project->video_url) }};
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
            @elseif($project->image)
                <div class="rounded-2xl overflow-hidden mb-12 shadow-lg">
                    <img src="{{ asset('storage/' . $project->image) }}"
                         alt="{{ $project->title }}"
                         class="w-full h-auto object-cover">
                </div>
            @endif

            @if($project->description)
                <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-serif prose-a:text-indigo-600 dark:prose-a:text-indigo-400">
                    {!! nl2br(e($project->description)) !!}
                </div>
            @endif

            <footer class="mt-16 pt-8 border-t border-zinc-200 dark:border-zinc-700 flex flex-wrap gap-4">
                @if($project->url)
                    <a href="{{ $project->url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                        View live project
                        <span>→</span>
                    </a>
                @endif
                <a href="{{ url('/#contact') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-zinc-300 dark:border-zinc-600 hover:border-indigo-500 dark:hover:border-indigo-500 text-zinc-700 dark:text-zinc-300 font-semibold rounded-xl transition-colors">
                    Get in touch
                </a>
            </footer>
        </div>
    </article>
@endsection
