<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data
      x-bind:class="{ 'dark': $store.theme.dark }"
      x-bind:style="$store.theme.dark ? '--theme: dark' : '--theme: light'">
<head>
    {{-- Evitar flash: aplicar tema antes de que Alpine cargue --}}
    <script>
        (function() {
            const stored = localStorage.getItem('portfolio-dark');
            const dark = stored !== null
                ? stored === 'true'
                : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription ?? config('app.name') }}">

    <title>{{ $metaTitle ?? config('app.name') }}</title>

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $metaTitle ?? config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription ?? config('app.name') }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    @if($ogImage ?? null)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('portfolio.components.seo-schema')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&family=cormorant-garamond:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/portfolio.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fafaf9] dark:bg-[#0c0c0f] text-zinc-800 dark:text-zinc-200 font-sans antialiased transition-colors duration-500">
    @include('portfolio.components.nav')

    <main>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
