<x-layout :title="__('site.seo.gallery')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.galleries.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.galleries.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.galleries.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleries as $gallery)
                <a href="{{ lroute('galleries.show', ['gallery' => $gallery]) }}" class="group relative overflow-hidden rounded-2xl">
                    <x-cover :path="$gallery->cover_path" :alt="$gallery->tr('title')" class="h-56 w-full transition duration-300 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-300">
                            {{ __('site.galleries.'.($gallery->type === 'video' ? 'video' : 'photo')) }} · {{ $gallery->items_count }} {{ __('site.common.items') }}
                        </p>
                        <h2 class="mt-1 font-bold text-white">{{ $gallery->tr('title') }}</h2>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">{{ __('site.galleries.empty') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $galleries->links() }}</div>
    </section>
</x-layout>
