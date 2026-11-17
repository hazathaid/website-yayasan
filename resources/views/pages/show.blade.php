<x-layout :title="$page->tr('title')" :meta-title="$page->tr('meta_title') ?: null" :meta-description="$page->tr('meta_description')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-14">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ setting('site_name', 'Yayasan') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ $page->tr('title') }}</h1>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-12">
        <div class="prose-content">
            {!! $page->tr('content') !!}
        </div>
    </section>
</x-layout>
