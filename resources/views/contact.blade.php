<x-layout :title="__('site.seo.contact')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.contact.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.contact.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.contact.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-emerald-600">{{ __('site.contact.secretariat') }}</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div>
                                <dt class="font-medium text-slate-500">{{ __('site.contact.address') }}</dt>
                                <dd class="mt-0.5 text-slate-800">{{ setting('address', '-') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-500">{{ __('site.contact.phone') }}</dt>
                                <dd class="mt-0.5 text-slate-800">{{ setting('phone', '-') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-slate-500">{{ __('site.contact.email') }}</dt>
                                <dd class="mt-0.5 text-slate-800">{{ setting('email', '-') }}</dd>
                            </div>
                            @if (setting('whatsapp'))
                                <div>
                                    <dt class="font-medium text-slate-500">{{ __('site.contact.whatsapp') }}</dt>
                                    <dd class="mt-0.5 text-slate-800">
                                        <a href="https://wa.me/{{ setting('whatsapp') }}" class="text-emerald-700 hover:underline" target="_blank" rel="noopener">
                                            {{ setting('whatsapp') }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>

                        @if (setting('facebook') || setting('instagram') || setting('youtube'))
                            <div class="mt-5 border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('site.contact.social') }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $key => $label)
                                        @if (setting($key))
                                            <a href="{{ setting($key) }}" target="_blank" rel="noopener"
                                               class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-emerald-400 hover:text-emerald-700">
                                                {{ $label }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (setting('maps_embed'))
                        <div class="overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                            <iframe src="{{ setting('maps_embed') }}" class="h-64 w-full" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade" title="{{ setting('site_name', 'Yayasan') }}"></iframe>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('site.contact.form_title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('site.contact.form_subtitle') }}</p>

                    @if ($errors->any())
                        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ lroute('contact.store') }}" class="mt-6 space-y-4">
                        @csrf
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="label">{{ __('site.contact.name') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input">
                            </div>
                            <div>
                                <label for="email" class="label">{{ __('site.labels.email') }} <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input">
                            </div>
                            <div>
                                <label for="phone" class="label">{{ __('site.labels.phone') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input">
                            </div>
                            <div>
                                <label for="subject" class="label">{{ __('site.contact.subject') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required class="input">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="label">{{ __('site.contact.message') }} <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="6" required class="input">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn-primary">{{ __('site.contact.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>
