@php
    $links = [
        ['label' => __('site.nav.home'), 'route' => 'home', 'active' => 'home'],
        ['label' => __('site.nav.about'), 'route' => 'about', 'active' => 'about'],
        ['label' => __('site.nav.programs'), 'route' => 'programs.index', 'active' => 'programs.*'],
        ['label' => __('site.nav.news'), 'route' => 'posts.index', 'active' => 'posts.*'],
        ['label' => __('site.nav.gallery'), 'route' => 'galleries.index', 'active' => 'galleries.*'],
        ['label' => __('site.nav.downloads'), 'route' => 'downloads.index', 'active' => 'downloads.*'],
        ['label' => __('site.nav.contact'), 'route' => 'contact.index', 'active' => 'contact.*'],
    ];
    $currentName = request()->route()?->getName() ?? '';
    $currentName = str_starts_with($currentName, 'en.') ? substr($currentName, 3) : $currentName;
@endphp
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
        <a href="{{ lroute('home') }}" class="flex items-center gap-2.5">
            @if (setting('logo_path'))
                <img src="{{ media_url(setting('logo_path')) }}" alt="{{ setting('site_name', 'Yayasan') }}"
                     class="h-10 w-10 rounded-xl bg-emerald-50 object-contain p-1">
            @else
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white">
                    {{ Str::substr(setting('site_name', 'Yayasan'), 0, 1) }}
                </span>
            @endif
            <span class="leading-tight">
                <span class="block text-sm font-bold text-slate-900">{{ setting('site_name', 'Yayasan') }}</span>
                <span class="block text-xs text-slate-500">{{ setting_localized('tagline', 'Bergerak bersama') }}</span>
            </span>
        </a>

        <nav class="hidden items-center gap-1 md:flex">
            @foreach ($links as $link)
                <a href="{{ lroute($link['route']) }}"
                   class="rounded-full px-3.5 py-2 text-sm font-medium transition {{ Str::is($link['active'], $currentName) ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div class="ml-1 flex items-center gap-1 rounded-full border border-slate-200 p-1">
                @foreach (['id' => 'ID', 'en' => 'EN'] as $locale => $label)
                    <a href="{{ locale_url($locale) }}"
                       class="rounded-full px-2.5 py-1 text-xs font-semibold {{ app()->getLocale() === $locale ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:text-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <a href="{{ lroute('donations.index') }}" class="btn-primary ml-2 !px-5 !py-2.5">{{ __('site.nav.donate') }}</a>
        </nav>

        <button type="button" data-menu-toggle aria-expanded="false" aria-label="{{ __('site.nav.menu') }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 md:hidden">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>
    </div>

    <nav data-menu class="hidden border-t border-slate-200 bg-white px-4 py-3 md:hidden">
        <div class="flex flex-col gap-1">
            @foreach ($links as $link)
                <a href="{{ lroute($link['route']) }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium {{ Str::is($link['active'], $currentName) ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div class="mt-2 flex gap-2">
                @foreach (['id' => 'Indonesia', 'en' => 'English'] as $locale => $label)
                    <a href="{{ locale_url($locale) }}"
                       class="flex-1 rounded-lg border px-3 py-2 text-center text-sm font-medium {{ app()->getLocale() === $locale ? 'border-emerald-600 bg-emerald-50 text-emerald-700' : 'border-slate-200 text-slate-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <a href="{{ lroute('donations.index') }}" class="btn-primary mt-2">{{ __('site.nav.donate_now') }}</a>
        </div>
    </nav>
</header>
