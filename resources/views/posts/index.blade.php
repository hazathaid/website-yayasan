<x-layout :title="__('site.seo.news')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.posts.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.posts.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.posts.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" action="{{ lroute('posts.index') }}" class="flex w-full max-w-md gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('site.posts.search_placeholder') }}"
                       class="input">
                @if (request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                @if (request('tag'))
                    <input type="hidden" name="tag" value="{{ request('tag') }}">
                @endif
                <button type="submit" class="btn-primary !px-5">{{ __('site.common.search') }}</button>
            </form>

            <div class="flex flex-wrap gap-2">
                <a href="{{ lroute('posts.index') }}"
                   class="rounded-full px-4 py-2 text-sm font-medium {{ ! request('kategori') ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ __('site.common.all') }}
                </a>
                @foreach ($categories as $category)
                    <a href="{{ lroute('posts.index', array_filter(['kategori' => $category->slug, 'q' => request('q'), 'tag' => request('tag')])) }}"
                       class="rounded-full px-4 py-2 text-sm font-medium {{ request('kategori') === $category->slug ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                        {{ $category->tr('name') }}
                    </a>
                @endforeach
            </div>
        </div>

        @if ($tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('site.posts.tags') }}</span>
                @foreach ($tags as $tag)
                    <a href="{{ lroute('posts.index', array_filter(['tag' => $tag->slug, 'kategori' => request('kategori'), 'q' => request('q')])) }}"
                       class="rounded-full px-3 py-1.5 text-xs font-medium {{ request('tag') === $tag->slug ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                        #{{ $tag->tr('name') }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <a href="{{ lroute('posts.show', ['post' => $post]) }}" class="card group">
                    <x-cover :path="$post->cover_path" :alt="$post->tr('title')" class="h-44 w-full" />
                    <div class="p-5">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            @if ($post->category)
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-600">{{ $post->category->tr('name') }}</span>
                            @endif
                            <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                        </div>
                        <h2 class="mt-3 text-lg font-bold leading-snug text-slate-900 group-hover:text-emerald-700">{{ $post->tr('title') }}</h2>
                        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-600">{{ $post->tr('excerpt') }}</p>
                        @if ($post->tags->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($post->tags as $tag)
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700">#{{ $tag->tr('name') }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">{{ __('site.posts.empty') }}</p>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    </section>
</x-layout>
