<x-layout>
    <section class="relative overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-900"></div>
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-teal-300/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-20 sm:py-28">
            <div class="max-w-3xl">
                <span class="inline-flex rounded-full border border-white/25 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-emerald-100">
                    {{ setting_localized('tagline', 'Bergerak bersama, tumbuh bersama') }}
                </span>
                <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                    {{ __('site.home.hero_1') }} <span class="text-emerald-300">{{ __('site.home.hero_2') }}</span> {{ __('site.home.hero_3') }}
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-emerald-50/90">
                    {{ setting_localized('description', 'Yayasan sosial yang bergerak di bidang pendidikan, kesehatan, dan pemberdayaan masyarakat.') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ lroute('donations.index') }}" class="btn-primary !bg-white !text-emerald-800 hover:!bg-emerald-50">{{ __('site.nav.donate_now') }}</a>
                    <a href="{{ lroute('programs.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        {{ __('site.home.view_programs') }}
                    </a>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ([
                    ['label' => __('site.home.stat_programs'), 'value' => number_format($stats['programs'])],
                    ['label' => __('site.home.stat_posts'), 'value' => number_format($stats['posts'])],
                    ['label' => __('site.home.stat_donors'), 'value' => number_format($stats['donors'])],
                    ['label' => __('site.home.stat_donations'), 'value' => rupiah($stats['donations'])],
                ] as $stat)
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                        <p class="text-xl font-bold text-white sm:text-2xl">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wider text-emerald-100/80">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">{{ __('site.home.programs_kicker') }}</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ __('site.home.programs_title') }}</h2>
            </div>
            <a href="{{ lroute('programs.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">{{ __('site.home.view_all_programs') }} &rarr;</a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach ($programs as $program)
                <a href="{{ lroute('programs.show', ['program' => $program]) }}" class="card group">
                    <x-cover :path="$program->cover_path" :alt="$program->tr('title')" class="h-44 w-full" />
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ __('site.status.'.$program->status) }}
                            </span>
                            <span class="text-xs font-medium text-slate-500">{{ $program->progress() }}%</span>
                        </div>
                        <h3 class="mt-3 text-lg font-bold text-slate-900 group-hover:text-emerald-700">{{ $program->tr('title') }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $program->tr('description') }}</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $program->progress() }}%"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">
                            {{ __('site.programs.collected') }} {{ rupiah($program->collected_amount) }}
                            @if ($program->target_amount)
                                {{ __('site.programs.of_target') }} {{ rupiah($program->target_amount) }}
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">{{ __('site.home.news_kicker') }}</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ __('site.home.news_title') }}</h2>
                </div>
                <a href="{{ lroute('posts.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">{{ __('site.home.view_all_news') }} &rarr;</a>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @foreach ($posts as $post)
                    <a href="{{ lroute('posts.show', ['post' => $post]) }}" class="card group">
                        <x-cover :path="$post->cover_path" :alt="$post->tr('title')" class="h-40 w-full" />
                        <div class="p-5">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                @if ($post->category)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">{{ $post->category->tr('name') }}</span>
                                @endif
                                <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                            </div>
                            <h3 class="mt-3 text-lg font-bold leading-snug text-slate-900 group-hover:text-emerald-700">{{ $post->tr('title') }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $post->tr('excerpt') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-16">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($galleries as $gallery)
                <a href="{{ lroute('galleries.show', ['gallery' => $gallery]) }}" class="group relative overflow-hidden rounded-2xl">
                    <x-cover :path="$gallery->cover_path" :alt="$gallery->tr('title')" class="h-64 w-full transition duration-300 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-300">
                            {{ __('site.galleries.'.($gallery->type === 'video' ? 'video' : 'photo')) }} · {{ $gallery->items_count }} {{ __('site.common.items') }}
                        </p>
                        <h3 class="mt-1 text-xl font-bold text-white">{{ $gallery->tr('title') }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 pb-20">
        <div class="overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 px-6 py-12 text-center sm:px-12">
            <h2 class="text-3xl font-bold text-white">{{ __('site.home.cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-emerald-50">{{ __('site.home.cta_text') }}</p>
            <a href="{{ lroute('donations.index') }}" class="btn-primary mt-7 !bg-white !text-emerald-800 hover:!bg-emerald-50">{{ __('site.home.cta_button') }}</a>
        </div>
    </section>
</x-layout>
