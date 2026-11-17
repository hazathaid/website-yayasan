# Plan: Website Yayasan

> Dokumen perencanaan sebelum coding. Status: draft.
> Tanggal: 17 November 2026

---

## 1. Ringkasan Proyek

Membangun website resmi yayasan untuk:

- Menampilkan profil, visi-misi, struktur organisasi
- Publikasi program & kegiatan
- Berita / artikel / pengumuman
- Galeri foto & video
- Halaman donasi (opsional transfer manual dulu, payment gateway nanti)
- Form kontak / pesan
- Panel admin (CMS) agar staf non-teknis bisa update konten sendiri

Target: website cepat, mobile-friendly, SEO-friendly, mudah dirawat, biaya hosting rendah.

---

## 2. Rekomendasi Bahasa & Stack

### Rekomendasi utama (cocok & familiar)

| Layer        | Pilihan                                  | Alasan |
|--------------|------------------------------------------|--------|
| Bahasa       | **PHP 8.3**                              | Ekosistem hosting murah di Indonesia, banyak developer, stabil untuk website profil |
| Framework    | **Laravel 11**                           | Routing, ORM, auth, validasi, queue, email siap pakai |
| Admin/CMS    | **Filament 3**                           | Panel admin modern tanpa banyak coding, CRUD otomatis |
| Template     | **Blade + Tailwind CSS**                 | Ringan, tanpa build rumit, mudah dikustom |
| Database     | **MySQL 8** (atau SQLite untuk dev)      | Standar, mudah backup, tersedia di semua hosting |
| Asset build  | **Vite** (bawaan Laravel)                | Bundling CSS/JS cepat |
| Storage      | Local disk / S3-compatible (opsional)    | Upload gambar berita, galeri, dokumen |

Tambahan: Alpine.js untuk interaksi kecil (menu mobile, slider) tanpa perlu framework JS besar.

### Alternatif (pilih sesuai tim)

| Opsi | Kapan dipilih | Kelebihan | Kekurangan |
|------|---------------|-----------|------------|
| **WordPress** | Staf sangat non-teknis, butuh cepat | Cepat jadi, plugin donasi/SEO banyak | Perlu maintenance plugin, keamanan jadi tanggung jawab |
| **Next.js + Sanity/Strapi** | Tim JS/React, butuh frontend modern | Performa & SEO bagus | Lebih kompleks, butuh server Node, biaya lebih tinggi |
| **Ruby on Rails** | Tim sudah kuat Ruby | Developer experience bagus | Hosting lebih mahal, talent lebih sedikit |

**Kesimpulan: Laravel + Filament + Tailwind** — paling pas untuk kebutuhan yayasan dan skill yang sudah ada (folder `laravel/` & `php/` di workspace).

---

## 3. Daftar Fitur

### 3.1 Halaman Publik

- **Home**: hero, highlight program, berita terbaru, CTA donasi
- **Tentang**: sejarah, visi & misi, struktur organisasi, legalitas (akta, SK Kemenkumham)
- **Program**: daftar program + detail (deskripsi, target, status)
- **Berita**: list + detail, kategori/tag, pencarian, pagination
- **Galeri**: album foto & video (embed YouTube)
- **Donasi**: info rekening, konfirmasi transfer (upload bukti), laporan donasi
- **Kontak**: form, alamat, peta (Google Maps embed), sosial media
- **Unduhan**: dokumen/laporan tahunan (PDF)
- **Halaman statis**: kebijakan privasi, syarat & ketentuan (opsional)

### 3.2 Panel Admin (CMS)

- Login + role (super admin, editor)
- CRUD: berita, kategori, program, galeri, halaman statis, pengurus
- Manajemen donasi: verifikasi bukti transfer, rekap
- Manajemen pesan masuk dari form kontak
- Pengaturan situs: nama, logo, kontak, sosmed, SEO meta default
- Upload media (gambar/dokumen) dengan validasi ukuran & tipe
- WYSIWYG editor untuk konten berita

### 3.3 Fitur Teknis

- SEO: meta title/description per halaman, Open Graph, sitemap.xml, robots.txt
- Responsive (mobile-first)
- Dark/light mode (opsional, nice-to-have)
- Pencarian (Laravel Scout / LIKE query sederhana)
- Rate limit form kontak (anti-spam + honeypot/captcha)
- Backup database terjadwal

---

## 4. Skema Database (ringkas)

```
users            (id, name, email, password, role, timestamps)
settings         (key, value)                     -- konfigurasi situs
pages            (id, slug, title, content, meta_title, meta_desc, is_published)
posts            (id, slug, title, excerpt, content, cover_path, category_id,
                  user_id, published_at, is_published)
categories       (id, slug, name)
programs         (id, slug, title, description, cover_path, target_amount,
                  status, started_at, ended_at)
program_images   (id, program_id, path, sort_order)
galleries        (id, title, type[foto|video], cover_path, created_at)
gallery_items    (id, gallery_id, path, video_url, caption, sort_order)
donations        (id, donor_name, email, phone, amount, method,
                  proof_path, status[pending|verified|rejected], note, verified_by)
contacts         (id, name, email, phone, subject, message, is_read)
team_members     (id, name, position, photo_path, sort_order)
```

Relasi utama: `posts -> categories`, `programs -> program_images`,
`galleries -> gallery_items`, `donations -> users` (verifier).

---

## 5. Struktur Folder (Laravel)

```
app/
  Filament/Resources/        # admin CRUD
  Http/Controllers/          # controller publik
  Http/Requests/             # validasi
  Models/
  Services/                  # logika bisnis (upload, donasi)
database/migrations, seeders
resources/views/
  layouts/app.blade.php
  components/                # navbar, footer, card
  pages/                     # home, about, program, berita, galeri, donasi, kontak
routes/web.php
public/
  storage/                   # symlink upload
tests/Feature, Unit
```

---

## 6. Roadmap & Estimasi

Asumsi 1 developer, part-time (±4 jam/hari).

| Fase | Aktivitas | Estimasi |
|------|-----------|----------|
| 0. Discovery | Konfirmasi konten, struktur menu, referensi desain, domain & hosting | 2–3 hari |
| 1. Setup | Init Laravel, Filament, Tailwind, repo Git, CI dasar | 1 hari |
| 2. Database & Auth | Migrasi, seeder, role admin | 2 hari |
| 3. Admin CMS | Semua resource Filament (berita, program, galeri, donasi, halaman, settings) | 5–6 hari |
| 4. Frontend publik | Layout, home, tentang, program, berita, galeri | 6–8 hari |
| 5. Donasi & Kontak | Form, upload bukti, verifikasi admin, email notifikasi | 3–4 hari |
| 6. SEO & Polish | Meta, sitemap, optimasi gambar, responsive QA | 2–3 hari |
| 7. Testing & UAT | Feature test, uji manual staf, perbaikan | 3 hari |
| 8. Deploy | Hosting, domain, SSL, backup, monitoring | 1–2 hari |

**Total: ± 25–30 hari kerja** (± 5–6 minggu part-time).
Kalau full-time: ± 3–4 minggu.

---

## 7. Non-Fungsional

- **Performa**: lazy-load gambar, kompres WebP, cache query, target LCP < 2.5s
- **Keamanan**: CSRF, validasi upload (mime + ukuran), hash password, rate limit login, backup off-site
- **SEO**: URL bersih (slug), sitemap, meta dinamis, struktur heading benar
- **Aksesibilitas**: kontras warna, alt text gambar, navigasi keyboard
- **Maintenance**: update dependency bulanan, log error (Sentry opsional)

---

## 8. Deployment & Biaya (perkiraan)

| Item | Opsi | Biaya |
|------|------|-------|
| Domain `.org` / `.id` | Namecheap / Rumahweb | Rp 150–250rb/tahun |
| Hosting | VPS kecil (1 vCPU/1GB) atau shared Laravel-ready | Rp 100–300rb/bulan |
| SSL | Let's Encrypt | Gratis |
| Email transaksional | Resend/Mailgun free tier | Gratis–rendah |
| CDN / storage gambar | Cloudflare / S3 opsional | Gratis–rendah |
| **Total awal** | | **± Rp 1.5–4 juta/tahun** |

Deploy: Git-based (GitHub Actions) → server, atau platform seperti Laravel Forge / Ploi / Vercel (untuk Next.js).

---

## 9. Risiko & Mitigasi

| Risiko | Mitigasi |
|--------|----------|
| Konten belum siap | Sediakan konten dummy, timeline konten dari yayasan |
| Staf sulit pakai CMS | Training singkat + dokumentasi video, UI Filament sederhana |
| Donasi disalahgunakan | Verifikasi manual admin, audit log, laporan transparan |
| Hosting lambat | Pilih hosting Laravel-ready, cache, CDN |
| Scope melebar | Tetapkan MVP dulu, fitur lanjutan masuk backlog |

---

## 10. MVP vs Backlog

**MVP (rilis pertama)**

- Profil, visi-misi, struktur
- Program + berita + galeri
- Donasi transfer manual + konfirmasi
- Form kontak
- Admin CMS dasar + settings
- SEO dasar

**Backlog (setelah rilis)**

- Payment gateway (Midtrans/Xendit)
- Multi-bahasa (ID/EN)
- Donasi berulang / campaign
- Newsletter
- Statistik pengunjung
- PWA / dark mode
- Integrasi WhatsApp notifikasi

---

## 11. Langkah Berikutnya

1. Konfirmasi stack (Laravel vs WordPress vs Next.js)
2. Kumpulkan konten & aset (logo, foto, teks profil)
3. Tentukan domain & hosting
4. Buat wireframe/mockup sederhana (Figma)
5. Setup repo + skeleton Laravel
6. Mulai Fase 2 (database & auth)

---

## 12. Pertanyaan Terbuka

1. Apakah ada preferensi bahasa/framework tertentu dari tim?
2. Butuh payment gateway sejak awal atau transfer manual cukup?
3. Siapa yang akan mengelola konten setelah rilis (teknis/non-teknis)?
4. Apakah perlu multi-bahasa?
5. Apakah sudah ada domain & hosting, atau perlu dibantu pilih?
6. Referensi website yayasan yang disukai (untuk arah desain)?
```
