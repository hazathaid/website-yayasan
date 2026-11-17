<x-layout :title="__('site.seo.downloads')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.downloads.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.downloads.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.downloads.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-12">
        <div class="space-y-4">
            @forelse ($downloads as $download)
                <article class="card flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $download->tr('title') }}</h2>
                        @if ($download->tr('description'))
                            <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $download->tr('description') }}</p>
                        @endif
                        <p class="mt-2 text-xs text-slate-400">{{ $download->updated_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <a href="{{ media_url($download->file_path) }}" download
                       class="btn-primary shrink-0 !px-5 !py-2.5">
                        {{ __('site.downloads.download') }}
                    </a>
                </article>
            @empty
                <p class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                    {{ __('site.downloads.empty') }}
                </p>
            @endforelse
        </div>

        <div class="mt-10">{{ $downloads->links() }}</div>
    </section>
</x-layout>
