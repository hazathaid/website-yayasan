<x-layout :title="__('site.seo.about')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.about.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.about.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ setting_localized('tagline', 'Bergerak bersama, tumbuh bersama.') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="prose-content">
                    @if ($page)
                        {!! $page->tr('content') !!}
                    @else
                        <p>{{ __('site.about.page_empty') }}</p>
                    @endif
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-emerald-600">{{ __('site.about.secretariat') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-slate-500">{{ __('site.about.address') }}</dt>
                            <dd class="mt-0.5 text-slate-800">{{ setting('address', '-') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-500">{{ __('site.about.phone') }}</dt>
                            <dd class="mt-0.5 text-slate-800">{{ setting('phone', '-') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-slate-500">{{ __('site.about.email') }}</dt>
                            <dd class="mt-0.5 text-slate-800">{{ setting('email', '-') }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </div>
    </section>

    @if ($visiMisi)
        <section class="bg-white py-14">
            <div class="mx-auto max-w-4xl px-4">
                <div class="prose-content">
                    {!! $visiMisi->tr('content') !!}
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-4 py-14">
        <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">{{ __('site.about.team_kicker') }}</p>
        <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ __('site.about.team_title') }}</h2>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($team as $member)
                <div class="card p-6 text-center">
                    @if ($member->photo_path)
                        <img src="{{ media_url($member->photo_path) }}" alt="{{ $member->name }}"
                             class="mx-auto h-24 w-24 rounded-full object-cover" loading="lazy">
                    @else
                        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-700">
                            {{ Str::substr($member->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="mt-4 font-bold text-slate-900">{{ $member->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $member->tr('position') }}</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">{{ __('site.about.team_empty') }}</p>
            @endforelse
        </div>
    </section>
</x-layout>
