<x-layout :title="__('site.seo.programs')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.programs.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.programs.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.programs.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="flex flex-wrap gap-2">
            @php
                $filters = [
                    '' => __('site.programs.filter_all'),
                    'active' => __('site.programs.filter_active'),
                    'completed' => __('site.programs.filter_completed'),
                    'paused' => __('site.programs.filter_paused'),
                ];
                $current = request('status', '');
            @endphp
            @foreach ($filters as $value => $label)
                <a href="{{ lroute('programs.index', $value ? ['status' => $value] : []) }}"
                   class="rounded-full px-4 py-2 text-sm font-medium {{ $current === $value ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($programs as $program)
                <a href="{{ lroute('programs.show', ['program' => $program]) }}" class="card group">
                    <x-cover :path="$program->cover_path" :alt="$program->tr('title')" class="h-44 w-full" />
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ __('site.status.'.$program->status) }}
                            </span>
                            <span class="text-xs font-medium text-slate-500">{{ $program->progress() }}%</span>
                        </div>
                        <h2 class="mt-3 text-lg font-bold text-slate-900 group-hover:text-emerald-700">{{ $program->tr('title') }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $program->tr('description') }}</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $program->progress() }}%"></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">{{ __('site.programs.collected') }} {{ rupiah($program->collected_amount) }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">{{ __('site.programs.empty') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $programs->links() }}</div>
    </section>
</x-layout>
