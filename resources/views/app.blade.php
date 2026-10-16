@php
    use App\Support\Seo\StructuredData;
    use App\Support\Settings\SiteBranding;

    try {
        $siteName = SiteBranding::siteName();
        $siteTagline = SiteBranding::siteTagline() ?: SiteBranding::journalFullName();
        $parentOrgName = SiteBranding::parentOrgName();
        $siteLogo = SiteBranding::siteLogoUrl() ?: SiteBranding::defaultLogoUrl();
        $seoBranding = SiteBranding::publicPayload();
    } catch (\Throwable) {
        $siteName = (string) config('app.name', 'SEC Journals');
        $siteTagline = (string) config('sicama.journal.full_name', 'Software Engineering Consortium Journals');
        $parentOrgName = (string) config('sicama.parent_org.short_name', 'SEC');
        $siteLogo = url('/images/sec-logo-square.png');
        $seoBranding = [
            'site_name' => $siteName,
            'site_tagline' => $siteTagline,
            'site_logo_url' => $siteLogo,
            'journal_full_name' => $siteTagline,
            'parent_org' => [
                'short_name' => $parentOrgName,
                'full_name' => (string) config('sicama.parent_org.full_name', ''),
                'motto' => (string) config('sicama.parent_org.motto', ''),
                'logo_url' => $siteLogo,
            ],
        ];
    }

    // Prefer square logo for search engines / OG when available.
    if (is_file(public_path('images/sec-logo-square.png'))) {
        $siteLogo = url('/images/sec-logo-square.png');
        $seoBranding['site_logo_url'] = $siteLogo;
        if (isset($seoBranding['parent_org']) && is_array($seoBranding['parent_org'])) {
            $seoBranding['parent_org']['logo_url'] = $siteLogo;
        }
    }

    $siteDescription = trim($siteName.' — peer-reviewed journals of the '.$parentOrgName.'. Fusion of Academia and Industry for productivity and advancement of society.');
    $canonicalUrl = rtrim((string) config('app.url'), '/').request()->getPathInfo();
    if ($canonicalUrl === rtrim((string) config('app.url'), '/')) {
        $canonicalUrl = rtrim((string) config('app.url'), '/').'/';
    }
    $logoDimensions = StructuredData::logoDimensions($siteLogo);
    $structuredData = StructuredData::graph($seoBranding, rtrim((string) config('app.url'), '/'), $siteDescription);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteName }} — {{ $siteTagline }}</title>
    <meta name="description" content="{{ $siteDescription }}">
    <meta name="keywords" content="{{ $siteName }}, {{ $parentOrgName }}, Software Engineering Consortium, software engineering journal, artificial intelligence, electrical engineering, academic journal Nigeria">
    <meta name="author" content="{{ $siteName }}">
    <meta name="application-name" content="{{ $siteName }}">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
    <meta name="googlebot" content="index,follow">
    <meta name="theme-color" content="#121212">
    <meta name="msapplication-TileColor" content="#121212">
    <meta name="msapplication-TileImage" content="{{ url('/images/sec-logo.png') }}">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" href="{{ url('/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ url('/images/sec-logo-512.png') }}" sizes="512x512">
    <link rel="apple-touch-icon" href="{{ url('/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}">
    <link rel="manifest" href="{{ url('/site.webmanifest') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $siteName }} — {{ $siteTagline }}">
    <meta property="og:description" content="{{ $siteDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="en_NG">
    <meta property="og:image" content="{{ $siteLogo }}">
    <meta property="og:image:secure_url" content="{{ $siteLogo }}">
    <meta property="og:image:type" content="{{ $logoDimensions['mime'] }}">
    <meta property="og:image:width" content="{{ $logoDimensions['width'] }}">
    <meta property="og:image:height" content="{{ $logoDimensions['height'] }}">
    <meta property="og:image:alt" content="{{ $siteName }} logo">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteName }} — {{ $siteTagline }}">
    <meta name="twitter:description" content="{{ $siteDescription }}">
    <meta name="twitter:image" content="{{ $siteLogo }}">
    <meta name="twitter:image:alt" content="{{ $siteName }} logo">

    <script type="application/ld+json">@json($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- JSON payload only (not executable) so production CSP script-src 'self' stays intact --}}
    @include('partials.app-seo-data')
</head>
<body class="font-sans antialiased bg-surface-muted text-text-primary">
    <div id="app"></div>
</body>
</html>
