<x-layout :title="__('site.seo.donations')">
    <section class="bg-emerald-700">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-200">{{ __('site.donations.kicker') }}</p>
            <h1 class="mt-2 text-4xl font-bold text-white">{{ __('site.donations.title') }}</h1>
            <p class="mt-3 max-w-2xl text-emerald-50/90">{{ __('site.donations.subtitle') }}</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 p-6 text-white shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">{{ __('site.donations.total_label') }}</p>
                <p class="mt-2 text-3xl font-bold">{{ rupiah($total) }}</p>
                <p class="mt-1 text-sm text-emerald-100">{{ __('site.donations.transactions', ['count' => number_format($count)]) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('site.donations.bank_label') }}</p>
                <p class="mt-2 text-lg font-bold text-slate-900">{{ setting('bank_name', 'Bank') }}</p>
                <p class="mt-1 font-mono text-2xl font-bold tracking-wide text-emerald-700">{{ setting('bank_account', '-') }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ __('site.donations.holder', ['name' => setting('bank_holder', setting('site_name', 'Yayasan'))]) }}</p>
            </div>
        </div>

        <div class="mt-10 grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-bold text-slate-900">{{ __('site.donations.form_title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('site.donations.form_subtitle') }}</p>

                    @if ($errors->any())
                        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ lroute('donations.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div>
                            <label for="program_id" class="label">{{ __('site.donations.program') }}</label>
                            <select id="program_id" name="program_id" class="input">
                                <option value="">{{ __('site.donations.program_none') }}</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}" @selected((string) old('program_id') === (string) $program->id)>
                                        {{ $program->tr('title') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="donor_name" class="label">{{ __('site.donations.donor_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required class="input">
                            </div>
                            <div>
                                <label for="amount" class="label">{{ __('site.donations.amount') }} <span class="text-red-500">*</span></label>
                                <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="1000" step="1000" required class="input">
                            </div>
                            <div>
                                <label for="email" class="label">{{ __('site.labels.email') }} <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="input">
                            </div>
                            <div>
                                <label for="phone" class="label">{{ __('site.labels.phone') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input">
                            </div>
                        </div>

                        <div>
                            <label for="proof" class="label">{{ __('site.donations.proof') }} <span class="text-red-500">*</span></label>
                            <input type="file" id="proof" name="proof" accept=".jpg,.jpeg,.png,.pdf" required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-emerald-700">
                        </div>

                        <div>
                            <label for="note" class="label">{{ __('site.donations.note') }}</label>
                            <textarea id="note" name="note" rows="3" class="input">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit" class="btn-primary w-full">{{ __('site.donations.submit') }}</button>
                        <p class="text-center text-xs text-slate-500">{{ __('site.donations.privacy') }}</p>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">{{ __('site.donations.verified_title') }}</h2>
                    <ul class="mt-4 divide-y divide-slate-100">
                        @forelse ($donations as $donation)
                            <li class="py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $donation->donor_name }}</p>
                                        <p class="text-xs text-slate-500">
                                            {{ $donation->verified_at?->translatedFormat('d M Y') }}
                                            @if ($donation->program)
                                                &middot; {{ $donation->program->tr('title') }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-700">{{ rupiah($donation->amount) }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-slate-500">{{ __('site.donations.verified_empty') }}</li>
                        @endforelse
                    </ul>
                    <div class="mt-4">{{ $donations->links() }}</div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
