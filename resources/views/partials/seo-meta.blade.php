@php
    use Illuminate\Support\Facades\Storage;

    $seo = [];
    if (Storage::disk('local')->exists('seo-settings.json')) {
        $seo = json_decode(Storage::disk('local')->get('seo-settings.json'), true);
    }
@endphp

<!-- 🧭 Basic SEO -->
<title>{{ $seo['title'] . ' | ' . $title ?? config('app.name') }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
<meta name="author" content="{{ $seo['author'] ?? '' }}">
<meta name="robots" content="index, follow">
@if(!empty($seo['canonical_url']))
    <link rel="canonical" href="{{ $seo['canonical_url'] }}">
@endif
@if(!empty($seo['language']))
    <meta http-equiv="content-language" content="{{ $seo['language'] }}">
@endif
@if(!empty($seo['theme_color']))
    <meta name="theme-color" content="{{ $seo['theme_color'] }}">
@endif

<!-- 🟦 Open Graph -->
<meta property="og:type" content="{{ $seo['og:type'] ?? 'website' }}">
<meta property="og:title" content="{{ $seo['og:title'] ?? $seo['title'] ?? config('app.name') }}">
<meta property="og:description" content="{{ $seo['og:description'] ?? $seo['description'] ?? '' }}">
<meta property="og:image" content="{{ $seo['og:image'] ?? '' }}">
<meta property="og:url" content="{{ $seo['og:url'] ?? url()->current() }}">
<meta property="og:site_name" content="{{ $seo['og:site_name'] ?? config('app.name') }}">

<!-- 🐦 Twitter -->
<meta name="twitter:card" content="{{ $seo['twitter:card'] ?? 'summary_large_image' }}">
<meta name="twitter:site" content="{{ $seo['twitter:site'] ?? '' }}">
<meta name="twitter:creator" content="{{ $seo['twitter:creator'] ?? '' }}">
<meta name="twitter:title" content="{{ $seo['twitter:title'] ?? $seo['title'] ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $seo['twitter:description'] ?? $seo['description'] ?? '' }}">
<meta name="twitter:image" content="{{ $seo['twitter:image'] ?? '' }}">

<!-- 🧩 Google Search Console Verification -->
@if(!empty($seo['google_verification']))
    <meta name="google-site-verification" content="{{ $seo['google_verification'] }}">
@endif
