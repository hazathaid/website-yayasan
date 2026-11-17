<x-mail::message>
# Konfirmasi donasi baru

**Donatur:** {{ $donation->donor_name }}
**Email:** {{ $donation->email }}
@if ($donation->phone)
**Telepon:** {{ $donation->phone }}
@endif
**Nominal:** {{ rupiah($donation->amount) }}
@if ($donation->program)
**Program:** {{ $donation->program->tr('title') }}
@endif
@if ($donation->note)
**Catatan:** {{ $donation->note }}
@endif

Bukti transfer sudah diunggah dan menunggu verifikasi admin.

<x-mail::button :url="url('/admin/donations')">
Buka donasi di panel admin
</x-mail::button>

Terima kasih,
{{ setting('site_name', 'Yayasan') }}
</x-mail::message>
