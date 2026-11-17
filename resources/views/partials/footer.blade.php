<footer class="mt-16 border-t border-slate-200 bg-slate-900 text-slate-300">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 md:grid-cols-4">
        <div class="md:col-span-2">
            <div class="flex items-center gap-2.5">
                @if (setting('logo_path'))
                    <img src="{{ media_url(setting('logo_path')) }}" alt="{{ setting('site_name', 'Yayasan') }}"
                         class="h-10 w-10 rounded-xl bg-white object-contain p-1">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 text-lg font-bold text-white">
                        {{ Str::substr(setting('site_name', 'Yayasan'), 0, 1) }}
                    </span>
                @endif
                <span class="text-lg font-bold text-white">{{ setting('site_name', 'Yayasan') }}</span>
            </div>
            <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-400">
                {{ setting_localized('description', 'Yayasan sosial yang bergerak di bidang pendidikan, kesehatan, dan pemberdayaan masyarakat.') }}
            </p>
            <div class="mt-5 flex gap-3">
                @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $key => $label)
                    @if (setting($key))
                        <a href="{{ setting($key) }}" target="_blank" rel="noopener"
                           class="rounded-full border border-slate-700 px-4 py-1.5 text-xs font-medium text-slate-300 transition hover:border-emerald-500 hover:text-emerald-400">
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.links') }}</h3>
            @php($privacyPage = \App\Models\Page::published()->where('slug', 'kebijakan-privasi')->first())
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ lroute('about') }}" class="hover:text-emerald-400">{{ __('site.nav.about') }}</a></li>
                <li><a href="{{ lroute('programs.index') }}" class="hover:text-emerald-400">{{ __('site.nav.programs') }}</a></li>
                <li><a href="{{ lroute('posts.index') }}" class="hover:text-emerald-400">{{ __('site.nav.news') }}</a></li>
                <li><a href="{{ lroute('galleries.index') }}" class="hover:text-emerald-400">{{ __('site.nav.gallery') }}</a></li>
                <li><a href="{{ lroute('downloads.index') }}" class="hover:text-emerald-400">{{ __('site.nav.downloads') }}</a></li>
                <li><a href="{{ lroute('donations.index') }}" class="hover:text-emerald-400">{{ __('site.nav.donate') }}</a></li>
                <li><a href="{{ lroute('contact.index') }}" class="hover:text-emerald-400">{{ __('site.nav.contact') }}</a></li>
                @if ($privacyPage)
                    <li><a href="{{ lroute('pages.show', ['page' => $privacyPage]) }}" class="hover:text-emerald-400">{{ __('site.footer.privacy') }}</a></li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ __('site.footer.contact') }}</h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-400">
                @if (setting('address'))
                    <li>{{ setting('address') }}</li>
                @endif
                @if (setting('phone'))
                    <li>{{ __('site.footer.phone') }}: {{ setting('phone') }}</li>
                @endif
                @if (setting('email'))
                    <li>Email: <a href="mailto:{{ setting('email') }}" class="hover:text-emerald-400">{{ setting('email') }}</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ setting('site_name', 'Yayasan') }}. {{ __('site.footer.rights') }}</p>
            <a href="{{ url('/sitemap.xml') }}" class="hover:text-emerald-400">{{ __('site.footer.sitemap') }}</a>
        </div>
    </div>
</footer>
