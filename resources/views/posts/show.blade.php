<x-layout :title="$post->tr('title')" :meta-title="$post->tr('meta_title') ?: null" :meta-description="$post->tr('meta_description') ?: $post->tr('excerpt')" :og-image="media_url($post->cover_path)">
    <article class="mx-auto max-w-3xl px-4 py-12">
        <nav class="text-sm text-slate-500">
            <a href="{{ lroute('posts.index') }}" class="hover:text-emerald-700">{{ __('site.posts.back') }}</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">{{ $post->tr('title') }}</span>
        </nav>

        <div class="mt-6 flex flex-wrap items-center gap-2 text-xs text-slate-500">
            @if ($post->category)
                <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">{{ $post->category->tr('name') }}</span>
            @endif
            <span>{{ $post->published_at?->translatedFormat('d F Y') }}</span>
            @if ($post->author)
                <span>&middot; {{ __('site.common.by') }} {{ $post->author->name }}</span>
            @endif
        </div>

        @if ($post->tags->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <a href="{{ lroute('posts.index', ['tag' => $tag->slug]) }}"
                       class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-100">
                        #{{ $tag->tr('name') }}
                    </a>
                @endforeach
            </div>
        @endif

        <h1 class="mt-4 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">{{ $post->tr('title') }}</h1>

        @if ($post->tr('excerpt'))
            <p class="mt-4 text-lg leading-relaxed text-slate-600">{{ $post->tr('excerpt') }}</p>
        @endif

        <x-cover :path="$post->cover_path" :alt="$post->tr('title')" class="mt-8 h-72 w-full rounded-2xl" />

        <div class="prose-content mt-8">
            {!! $post->tr('content') !!}
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-14 border-t border-slate-200 pt-10">
                <h2 class="text-2xl font-bold text-slate-900">{{ __('site.posts.read_also') }}</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ lroute('posts.show', ['post' => $item]) }}" class="card p-4">
                            <p class="text-xs text-slate-500">{{ $item->published_at?->translatedFormat('d M Y') }}</p>
                            <h3 class="mt-1 text-sm font-bold text-slate-900">{{ $item->tr('title') }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
</x-layout>
