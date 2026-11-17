# Website Yayasan — MVP

Website profil resmi yayasan dengan halaman publik + panel admin (CMS) berbasis
**Laravel 11 + Filament 3 + Tailwind CSS**. Rencana lengkap ada di [PLAN.md](PLAN.md).

## Fitur MVP

**Halaman publik**

- Beranda (hero, statistik, program, berita, galeri, CTA donasi)
- Tentang (profil, visi & misi, pengurus)
- Program (daftar + filter status, detail + progres donasi)
- Berita (pencarian, filter kategori & tag, pagination, detail + berita terkait)
- Galeri foto & video (embed YouTube)
- Unduhan dokumen & laporan tahunan (PDF/Word/Excel)
- Donasi transfer manual + form konfirmasi (upload bukti, privat, opsional terikat program)
- Daftar donasi terverifikasi
- Kontak (form + peta + info sekretariat + notifikasi email)
- Halaman statis (visi-misi, kebijakan privasi, dll.)
- Multi-bahasa Indonesia/English (`/en/...`), language switcher, hreflang
- SEO dasar: meta title/description per halaman, Open Graph (termasuk gambar), canonical, `sitemap.xml`, `robots.txt` dinamis

**Panel admin** (`/admin`)

- Login + role (`super_admin`, `editor`)
- CRUD berita & kategori, tag, program (+ galeri program), galeri album, unduhan dokumen, halaman, pengurus
- Field terjemahan English untuk berita, tag, program, galeri, halaman, kategori, unduhan, dan pengurus
- Verifikasi / tolak donasi + buka bukti transfer (akses privat) + kaitkan donasi ke program
- Pesan masuk dari form kontak
- Pengaturan situs (identitas, logo, kontak, sosmed, rekening, SEO) + terjemahan English
- Manajemen pengguna (khusus super admin)

**Teknis**

- Responsive mobile-first, Tailwind CSS, Vite
- Upload media ke storage publik (`storage/app/public`), bukti transfer privat (`storage/app/private`)
- Honeypot + rate limit pada form kontak & donasi
- Notifikasi email admin untuk pesan kontak dan konfirmasi donasi baru
- Backup database otomatis (SQLite/MySQL/PostgreSQL) + retensi file
- Feature test untuk halaman publik, form, panel admin, dan backup

## Persyaratan

- PHP 8.2+ (diuji pada PHP 8.3)
- Composer 2
- Node.js 18+ & npm
- SQLite (default) atau MySQL 8

## Menjalankan (development)

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Buka http://localhost:8000 — panel admin di http://localhost:8000/admin.

### Akun demo

| Role        | Email                  | Password   |
|-------------|------------------------|------------|
| Super Admin | `admin@yayasan.test`   | `password` |
| Editor      | `editor@yayasan.test`  | `password` |

> Ganti password dan hapus akun demo sebelum produksi.

### Development dengan hot reload

```bash
composer run dev
```

## Testing

```bash
php artisan test
```

23 test mencakup: render halaman publik ID & EN, pencarian/filter tag berita, hreflang,
sitemap dua bahasa, meta title/OG image, robots.txt, form kontak (honeypot + email),
konfirmasi donasi + upload bukti + email + sinkronisasi program, kegagalan email
notifikasi tetap menyimpan data, halaman unduhan,
backup database, login admin, akses seluruh resource admin, dan pembatasan akses
pengguna untuk editor.

## Struktur singkat

```
app/
  Filament/                 # resource & halaman admin
  Http/Controllers/         # controller halaman publik
  Mail/                      # notifikasi email kontak & donasi
  Models/                    # Post, Program, Gallery, Donation, Download, Tag, ...
  Console/Commands/          # backup:database
  Support/helpers.php        # setting(), media_url(), rupiah()
database/
  migrations/                # skema database
  seeders/DatabaseSeeder.php # data contoh + akun admin
resources/views/
  components/layout.blade.php
  partials/                  # navbar, footer
  home, about, posts, programs, galleries, downloads, donations, contact, pages, mail, sitemap
routes/web.php
tests/Feature/
```

## Multi-bahasa

- Bahasa default: **Indonesia** tanpa prefix (`/berita`, `/program`).
- Bahasa Inggris: prefix `/en` (`/en/berita`, `/en/program`), dipilih lewat switcher di navbar.
- Konten CMS memiliki kolom `*_en`. Jika terjemahan English kosong, situs otomatis memakai konten Indonesia.
- Isi terjemahan di panel admin pada bagian **Terjemahan (English)** di setiap resource,
  dan **Pengaturan Situs → Terjemahan (English)** untuk tagline/deskripsi/meta.
- Layout publik menyertakan `hreflang` (id, en, x-default) dan sitemap memuat kedua bahasa.

## Konfigurasi produksi

1. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` ke domain asli.
2. Gunakan MySQL: ubah `DB_CONNECTION=mysql` + kredensial di `.env`.
3. Jalankan `php artisan migrate --force`, `php artisan storage:link`,
   `npm run build`, lalu `php artisan optimize`.
4. Atur mailer (mis. Resend/Mailgun) untuk notifikasi kontak & donasi.
5. Aktifkan scheduler dan backup database (lihat bagian berikut).

## Backup database

```bash
php artisan backup:database
```

- File backup disimpan di `storage/app/backups` (ubah lewat `BACKUP_PATH`).
- Retensi default 14 hari (ubah lewat `BACKUP_RETENTION_DAYS`).
- Jadwal harian pukul 02.00 sudah terdaftar di `routes/console.php`, jalankan
  scheduler lewat cron: `* * * * * cd /path/ke/proyek && php artisan schedule:run >> /dev/null 2>&1`.
- SQLite cukup menyalin file database; MySQL membutuhkan `mysqldump`, PostgreSQL membutuhkan `pg_dump`.
- Tetap backup folder `storage/app/` (media upload) secara terpisah.

## Backlog (setelah MVP)

Payment gateway (Midtrans/Xendit), campaign donasi, newsletter, statistik pengunjung,
notifikasi WhatsApp, PWA/dark mode.
