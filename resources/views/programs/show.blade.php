<x-layout :title="$program->tr('title')" :meta-description="$program->tr('description')" :og-image="media_url($program->cover_path)">
    <section class="bg-slate-900">
        <div class="mx-auto max-w-6xl px-4 py-14">
            <nav class="text-sm text-slate-400">
                <a href="{{ lroute('programs.index') }}" class="hover:text-emerald-400">{{ __('site.programs.back') }}</a>
                <span class="mx-2">/</span>
                <span class="text-slate-200">{{ $program->tr('title') }}</span>
            </nav>
            <div class="mt-6 grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-300">
                        {{ __('site.status.'.$program->status) }}
                    </span>
                    <h1 class="mt-4 text-3xl font-bold text-white sm:text-4xl">{{ $program->tr('title') }}</h1>
                    <p class="mt-4 max-w-2xl text-lg text-slate-300">{{ $program->tr('description') }}</p>
                </div>

                <div class="rounded-2xl border border-slate-700 bg-slate-800 p-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('site.programs.donation_collected') }}</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ rupiah($program->collected_amount) }}</p>
                    @if ($program->target_amount)
                        <p class="mt-1 text-sm text-slate-400">{{ __('site.programs.of_target') }} {{ rupiah($program->target_amount) }}</p>
                    @endif
                    <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-700">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $program->progress() }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-400">{{ $program->progress() }}% {{ __('site.programs.progress') }}</p>
                    <a href="{{ lroute('donations.index') }}" class="btn-primary mt-5 w-full">{{ __('site.programs.support') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($program->tr('content'))
                    <div class="prose-content">{!! $program->tr('content') !!}</div>
                @else
                    <p class="text-slate-600">{{ $program->tr('description') }}</p>
                @endif

                @if ($program->images->isNotEmpty())
                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        @foreach ($program->images as $image)
                            <img src="{{ media_url($image->path) }}" alt="{{ $program->tr('title') }}" loading="lazy"
                                 class="h-52 w-full rounded-xl object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">{{ __('site.programs.info') }}</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">{{ __('site.programs.status') }}</dt>
                            <dd class="font-medium text-slate-800">{{ __('site.status.'.$program->status) }}</dd>
                        </div>
                        @if ($program->started_at)
                            <div class="flex justify-between gap-3">
                                <dt class="text-slate-500">{{ __('site.programs.start') }}</dt>
                                <dd class="font-medium text-slate-800">{{ $program->started_at->translatedFormat('d M Y') }}</dd>
                            </div>
                        @endif
                        @if ($program->ended_at)
                            <div class="flex justify-between gap-3">
                                <dt class="text-slate-500">{{ __('site.programs.end') }}</dt>
                                <dd class="font-medium text-slate-800">{{ $program->ended_at->translatedFormat('d M Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </aside>
        </div>

        @if ($otherPrograms->isNotEmpty())
            <div class="mt-14">
                <h2 class="text-2xl font-bold text-slate-900">{{ __('site.programs.others') }}</h2>
                <div class="mt-6 grid gap-6 md:grid-cols-3">
                    @foreach ($otherPrograms as $other)
                        <a href="{{ lroute('programs.show', ['program' => $other]) }}" class="card group">
                            <x-cover :path="$other->cover_path" :alt="$other->tr('title')" class="h-40 w-full" />
                            <div class="p-5">
                                <h3 class="font-bold text-slate-900 group-hover:text-emerald-700">{{ $other->tr('title') }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $other->tr('description') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layout>
