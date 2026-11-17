@props([
    'title' => null,
    'metaTitle' => null,
    'metaDescription' => null,
    'ogImage' => null,
])

@php
    $siteName = setting('site_name', 'Yayasan');
    $pageTitle = $metaTitle ?: ($title ? $title.' — '.$siteName : $siteName.' — '.setting_localized('tagline', 'Bergerak bersama'));
    $description = $metaDescription ?? setting_localized('meta_description', setting_localized('description', 'Website resmi yayasan.'));
    $ogImageUrl = $ogImage ? (str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage)) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="id" href="{{ locale_url('id') }}">
    <link rel="alternate" hreflang="en" href="{{ locale_url('en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ locale_url('id') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_US' : 'id_ID' }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-800 antialiased">
    @include('partials.navbar')

    @if (session('success'))
        <div class="mx-auto mt-4 w-full max-w-5xl px-4">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')
</body>
</html>
