@php
    $props = $page['props'] ?? [];
    $locale = $props['locale'] ?? app()->getLocale();
    $meta = $props['meta'] ?? [];
    $path = '/'.ltrim(\Illuminate\Support\Str::after(request()->getPathInfo(), '/'.$locale), '/');
    $path = $path === '/' ? '' : $path;
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1b2a52">
        <title data-inertia="">{{ $meta['title'] ?? config('app.name') }}</title>
        <meta name="description" content="{{ $meta['description'] ?? '' }}" data-inertia="description">
        <meta property="og:type" content="website" data-inertia="og:type">
        <meta property="og:site_name" content="Elite Stone" data-inertia="og:site_name">
        <meta property="og:title" content="{{ $meta['title'] ?? '' }}" data-inertia="og:title">
        <meta property="og:description" content="{{ $meta['description'] ?? '' }}" data-inertia="og:description">
        <meta property="og:image" content="{{ $meta['image'] ?? '' }}" data-inertia="og:image">
        <meta property="og:url" content="{{ request()->url() }}" data-inertia="og:url">
        <meta property="og:locale" content="{{ $locale === 'ar' ? 'ar_EG' : 'en_US' }}" data-inertia="og:locale">
        <meta name="twitter:card" content="summary_large_image" data-inertia="twitter:card">
        <link rel="canonical" href="{{ request()->url() }}" data-inertia="canonical">
        <link rel="alternate" hreflang="en" href="{{ url('/en'.$path) }}" data-inertia="alt-en">
        <link rel="alternate" hreflang="ar" href="{{ url('/ar'.$path) }}" data-inertia="alt-ar">
        <link rel="alternate" hreflang="x-default" href="{{ url('/en'.$path) }}" data-inertia="alt-default">
        @foreach (($meta['schema'] ?? []) as $schema)
            <script type="application/ld+json" data-inertia="schema-{{ $loop->index }}">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
        @endforeach
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=vollkorn:500,600,700|manrope:400,500,600,700|ibm-plex-sans-arabic:400,500,600,700&display=swap">
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
