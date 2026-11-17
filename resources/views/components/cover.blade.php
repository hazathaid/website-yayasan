@props(['path' => null, 'alt' => '', 'class' => ''])

@if ($path)
    <img src="{{ media_url($path) }}" alt="{{ $alt }}" loading="lazy" class="{{ $class }} object-cover">
@else
    <div class="{{ $class }} flex items-center justify-center bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700">
        <span class="px-4 text-center text-xs font-semibold uppercase tracking-wider text-white/80">
            {{ $alt ?: setting('site_name', 'Yayasan') }}
        </span>
    </div>
@endif
