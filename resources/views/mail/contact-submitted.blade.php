<x-mail::message>
# Pesan kontak baru

**Nama:** {{ $contact->name }}
**Email:** {{ $contact->email }}
@if ($contact->phone)
**Telepon:** {{ $contact->phone }}
@endif
**Subjek:** {{ $contact->subject }}

{{ $contact->message }}

<x-mail::button :url="url('/admin/contacts')">
Buka pesan di panel admin
</x-mail::button>

Terima kasih,
{{ setting('site_name', 'Yayasan') }}
</x-mail::message>
