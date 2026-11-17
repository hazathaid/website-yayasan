<x-layout :title="$gallery->tr('title')" :meta-description="$gallery->tr('description')" :og-image="media_url($gallery->cover_path)">
    <section class="bg-slate-900">
        <div class="mx-auto max-w-6xl px-4 py-14">
            <nav class="text-sm text-slate-400">
                <a href="{{ lroute('galleries.index') }}" class="hover:text-emerald-400">{{ __('site.galleries.back') }}</a>
                <span class="mx-2">/</span>
                <span class="text-slate-200">{{ $gallery->tr('title') }}</span>
            </nav>
            <h1 class="mt-4 text-3xl font-bold text-white sm:text-4xl">{{ $gallery->tr('title') }}</h1>
            @if ($gallery->tr('description'))
                <p class="mt-3 max-w-2xl text-slate-300">{{ $gallery->tr('description') }}</p>
            @endif
            <p class="mt-2 text-sm text-slate-400">
                {{ __('site.galleries.'.($gallery->type === 'video' ? 'video' : 'photo')) }} · {{ $gallery->items->count() }} {{ __('site.common.items') }}
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($gallery->items as $item)
                <figure class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    @if ($item->video_url)
                        @if ($item->youtubeId())
                            <div class="aspect-video w-full">
                                <iframe class="h-full w-full" src="https://www.youtube.com/embed/{{ $item->youtubeId() }}"
                                        title="{{ $item->caption ?? $gallery->tr('title') }}" allowfullscreen loading="lazy"></iframe>
                            </div>
                        @else
                            <div class="flex aspect-video items-center justify-center bg-slate-100 p-4 text-center">
                                <a href="{{ $item->video_url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-emerald-700">
                                    {{ __('site.galleries.open_video') }} &rarr;
                                </a>
                            </div>
                        @endif
                    @else
                        <x-cover :path="$item->path" :alt="$item->caption ?? $gallery->tr('title')" class="h-60 w-full" />
                    @endif
                    @if ($item->caption)
                        <figcaption class="px-4 py-3 text-sm text-slate-600">{{ $item->caption }}</figcaption>
                    @endif
                </figure>
            @empty
                <p class="text-sm text-slate-500">{{ __('site.galleries.no_items') }}</p>
            @endforelse
        </div>
    </section>
</x-layout>
