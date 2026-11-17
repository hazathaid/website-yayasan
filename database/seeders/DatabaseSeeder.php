<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@yayasan.test'],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => 'super_admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'editor@yayasan.test'],
            [
                'name' => 'Editor Konten',
                'password' => 'password',
                'role' => 'editor',
            ],
        );

        $settings = [
            'site_name' => 'Yayasan Cahaya Nusantara',
            'tagline' => 'Bergerak bersama, tumbuh bersama.',
            'tagline_en' => 'Moving together, growing together.',
            'description' => 'Yayasan sosial yang bergerak di bidang pendidikan, kesehatan, dan pemberdayaan masyarakat.',
            'description_en' => 'A social foundation working in education, health, and community empowerment.',
            'address' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat 40111',
            'phone' => '(022) 123-4567',
            'email' => 'halo@yayasancahayanusantara.org',
            'whatsapp' => '6281234567890',
            'facebook' => 'https://facebook.com/',
            'instagram' => 'https://instagram.com/',
            'youtube' => 'https://youtube.com/',
            'bank_name' => 'Bank Mandiri',
            'bank_account' => '123-00-1234567-8',
            'bank_holder' => 'Yayasan Cahaya Nusantara',
            'meta_description' => 'Website resmi Yayasan Cahaya Nusantara: program pendidikan, kesehatan, dan pemberdayaan masyarakat.',
            'meta_description_en' => 'Official website of Yayasan Cahaya Nusantara: education, health, and community empowerment programs.',
            'maps_embed' => 'https://www.google.com/maps?q=Bandung&output=embed',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $categories = collect([
            'Kegiatan' => 'kegiatan',
            'Pengumuman' => 'pengumuman',
            'Prestasi' => 'prestasi',
            'Donasi' => 'donasi',
        ])->map(fn ($slug, $name) => Category::updateOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'name_en' => match ($slug) {
                'kegiatan' => 'Activities',
                'pengumuman' => 'Announcements',
                'prestasi' => 'Achievements',
                default => 'Donations',
            }],
        ))->keyBy('slug');

        if (Post::count() === 0) {
            $posts = [
                [
                    'title' => 'Penyaluran Beasiswa Semester Ganjil 2026',
                    'title_en' => 'Scholarship Disbursement for the 2026 Odd Semester',
                    'category' => 'kegiatan',
                    'excerpt' => 'Sebanyak 45 pelajar menerima beasiswa pendidikan dari program Cahaya Pintar.',
                    'excerpt_en' => 'Forty-five students received education scholarships from the Cahaya Pintar program.',
                    'content' => '<p>Alhamdulillah, pada bulan ini yayasan menyalurkan beasiswa kepada 45 pelajar dari keluarga prasejahtera di Bandung dan sekitarnya.</p><p>Program beasiswa ini mencakup biaya sekolah, seragam, serta pendampingan belajar rutin setiap akhir pekan.</p><p>Kami mengucapkan terima kasih kepada seluruh donatur yang telah mendukung program ini.</p>',
                    'content_en' => '<p>This month the foundation disbursed scholarships to 45 students from low-income families in Bandung and surrounding areas.</p><p>The scholarship covers school fees, uniforms, and regular weekend tutoring.</p><p>We thank all donors who have supported this program.</p>',
                ],
                [
                    'title' => 'Pemeriksaan Kesehatan Gratis di Desa Sukamaju',
                    'title_en' => 'Free Health Check-ups in Sukamaju Village',
                    'category' => 'kegiatan',
                    'excerpt' => 'Layanan kesehatan gratis diikuti lebih dari 200 warga.',
                    'excerpt_en' => 'More than 200 residents joined the free health services.',
                    'content' => '<p>Tim relawan medis yayasan menggelar pemeriksaan kesehatan gratis di Desa Sukamaju.</p><p>Layanan meliputi cek tekanan darah, gula darah, konsultasi gizi, serta pembagian vitamin untuk lansia dan anak-anak.</p>',
                    'content_en' => '<p>The foundation medical volunteer team held free health check-ups in Sukamaju Village.</p><p>Services included blood pressure and blood sugar checks, nutrition consultation, and vitamin distribution for the elderly and children.</p>',
                ],
                [
                    'title' => 'Pendaftaran Relawan Pengajar Dibuka',
                    'title_en' => 'Volunteer Teacher Registration Is Open',
                    'category' => 'pengumuman',
                    'excerpt' => 'Kami membuka pendaftaran relawan pengajar untuk program kelas belajar sore.',
                    'excerpt_en' => 'We are opening registration for volunteer teachers for the afternoon learning program.',
                    'content' => '<p>Yayasan membuka kesempatan bagi siapa saja yang ingin berkontribusi sebagai relawan pengajar.</p><p>Kelas dilaksanakan setiap Selasa dan Kamis pukul 15.00–17.00 WIB.</p><p>Pendaftaran dapat dilakukan melalui halaman kontak.</p>',
                    'content_en' => '<p>The foundation invites anyone who wants to contribute as a volunteer teacher.</p><p>Classes are held every Tuesday and Thursday from 3 to 5 pm.</p><p>Register through the contact page.</p>',
                ],
                [
                    'title' => 'Laporan Donasi Publik Triwulan III 2026',
                    'title_en' => 'Public Donation Report — Q3 2026',
                    'category' => 'donasi',
                    'excerpt' => 'Transparansi penggunaan dana donasi periode Juli–September 2026.',
                    'excerpt_en' => 'Transparency report on the use of donation funds from July to September 2026.',
                    'content' => '<p>Sebagai bentuk transparansi, berikut ringkasan penggunaan dana donasi triwulan III 2026.</p><ul><li>Pendidikan: 55%</li><li>Kesehatan: 30%</li><li>Operasional: 15%</li></ul><p>Laporan lengkap dapat diminta melalui sekretariat yayasan.</p>',
                    'content_en' => '<p>As part of our transparency commitment, here is a summary of donation funds used in Q3 2026.</p><ul><li>Education: 55%</li><li>Health: 30%</li><li>Operations: 15%</li></ul><p>The full report is available upon request at the foundation secretariat.</p>',
                ],
            ];

            foreach ($posts as $index => $post) {
                Post::create([
                    'category_id' => $categories[$post['category']]->id,
                    'user_id' => $admin->id,
                    'title' => $post['title'],
                    'title_en' => $post['title_en'],
                    'excerpt' => $post['excerpt'],
                    'excerpt_en' => $post['excerpt_en'],
                    'content' => $post['content'],
                    'content_en' => $post['content_en'],
                    'is_published' => true,
                    'published_at' => now()->subDays($index + 1),
                ]);
            }
        }

        if (Tag::count() === 0) {
            $tagIds = collect([
                ['slug' => 'pendidikan', 'name' => 'Pendidikan', 'name_en' => 'Education'],
                ['slug' => 'kesehatan', 'name' => 'Kesehatan', 'name_en' => 'Health'],
                ['slug' => 'relawan', 'name' => 'Relawan', 'name_en' => 'Volunteers'],
                ['slug' => 'transparansi', 'name' => 'Transparansi', 'name_en' => 'Transparency'],
            ])->mapWithKeys(fn (array $tag) => [
                $tag['slug'] => Tag::create($tag)->id,
            ]);

            $postTags = [
                'Penyaluran Beasiswa Semester Ganjil 2026' => ['pendidikan'],
                'Pemeriksaan Kesehatan Gratis di Desa Sukamaju' => ['kesehatan', 'relawan'],
                'Pendaftaran Relawan Pengajar Dibuka' => ['relawan', 'pendidikan'],
                'Laporan Donasi Publik Triwulan III 2026' => ['transparansi'],
            ];

            foreach ($postTags as $title => $slugs) {
                Post::where('title', $title)
                    ->first()
                    ?->tags()
                    ->sync(collect($slugs)->map(fn (string $slug) => $tagIds[$slug])->all());
            }
        }

        if (Program::count() === 0) {
            Program::create([
                'title' => 'Cahaya Pintar',
                'title_en' => 'Bright Minds',
                'description' => 'Beasiswa dan pendampingan belajar untuk pelajar dari keluarga prasejahtera.',
                'description_en' => 'Scholarships and tutoring for students from low-income families.',
                'content' => '<p>Program Cahaya Pintar memberikan beasiswa pendidikan, perlengkapan sekolah, dan pendampingan belajar bagi pelajar berprestasi dari keluarga kurang mampu.</p><p>Setiap penerima beasiswa mendapatkan pendampingan rutin dan evaluasi belajar setiap bulan.</p>',
                'content_en' => '<p>Bright Minds provides scholarships, school supplies, and tutoring for high-achieving students from underprivileged families.</p><p>Each scholarship recipient receives regular mentoring and monthly learning evaluations.</p>',
                'target_amount' => 150000000,
                'collected_amount' => 87500000,
                'status' => 'active',
                'started_at' => now()->subMonths(3),
            ]);

            Program::create([
                'title' => 'Sehat Bersama',
                'title_en' => 'Healthy Together',
                'description' => 'Layanan kesehatan gratis dan penyuluhan gizi untuk masyarakat desa.',
                'description_en' => 'Free health services and nutrition education for rural communities.',
                'content' => '<p>Program Sehat Bersama menghadirkan pemeriksaan kesehatan gratis, penyuluhan gizi, dan pembagian vitamin di wilayah pedesaan.</p>',
                'content_en' => '<p>Healthy Together brings free health check-ups, nutrition education, and vitamin distribution to rural areas.</p>',
                'target_amount' => 80000000,
                'collected_amount' => 32000000,
                'status' => 'active',
                'started_at' => now()->subMonths(2),
            ]);

            Program::create([
                'title' => 'Ruang Belajar Sore',
                'title_en' => 'Afternoon Learning Space',
                'description' => 'Kelas belajar gratis dan perpustakaan mini untuk anak-anak.',
                'description_en' => 'Free classes and a mini library for children.',
                'content' => '<p>Kelas belajar sore menyediakan bimbingan belajar gratis, perpustakaan mini, dan kegiatan kreatif bagi anak-anak di lingkungan yayasan.</p>',
                'content_en' => '<p>The afternoon learning space offers free tutoring, a mini library, and creative activities for children in the neighborhood.</p>',
                'target_amount' => 50000000,
                'collected_amount' => 50000000,
                'status' => 'completed',
                'started_at' => now()->subYear(),
                'ended_at' => now()->subMonths(2),
            ]);
        }

        if (Gallery::count() === 0) {
            $kegiatan = Gallery::create([
                'title' => 'Penyaluran Beasiswa 2026',
                'title_en' => '2026 Scholarship Disbursement',
                'type' => 'foto',
                'description' => 'Dokumentasi kegiatan penyaluran beasiswa kepada pelajar.',
                'description_en' => 'Documentation of the scholarship disbursement for students.',
            ]);

            foreach (['Upacara pembukaan', 'Penyerahan simbolis', 'Foto bersama penerima beasiswa'] as $i => $caption) {
                $kegiatan->items()->create(['caption' => $caption, 'sort_order' => $i]);
            }

            $video = Gallery::create([
                'title' => 'Profil Singkat Yayasan',
                'title_en' => 'Foundation Profile',
                'type' => 'video',
                'description' => 'Video pengenalan program dan kegiatan yayasan.',
                'description_en' => 'A short video introducing the foundation programs and activities.',
            ]);

            $video->items()->create([
                'video_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                'caption' => 'Video profil yayasan',
                'sort_order' => 0,
            ]);
        }

        if (TeamMember::count() === 0) {
            foreach ([
                ['Dewi Lestari', 'Ketua Yayasan', 'Chairperson'],
                ['Ahmad Fauzi', 'Sekretaris', 'Secretary'],
                ['Rina Kartika', 'Bendahara', 'Treasurer'],
                ['Budi Santoso', 'Koordinator Program', 'Program Coordinator'],
            ] as $i => [$name, $position, $positionEn]) {
                TeamMember::create([
                    'name' => $name,
                    'position' => $position,
                    'position_en' => $positionEn,
                    'sort_order' => $i,
                ]);
            }
        }

        if (Page::count() === 0) {
            Page::create([
                'title' => 'Tentang Kami',
                'title_en' => 'About Us',
                'slug' => 'tentang-kami',
                'content' => '<p>Yayasan Cahaya Nusantara didirikan pada tahun 2015 oleh sekelompok profesional dan relawan yang peduli terhadap pendidikan dan kesejahteraan masyarakat.</p><p>Berawal dari kegiatan belajar bersama di lingkungan kampung, kini yayasan telah menjalankan program pendidikan, kesehatan, dan pemberdayaan masyarakat di beberapa wilayah.</p><p>Yayasan terdaftar secara resmi dengan Akta Notaris No. 12 Tahun 2015 dan SK Kemenkumham No. AHU-0001234.AH.01.04.Tahun 2015.</p>',
                'content_en' => '<p>Yayasan Cahaya Nusantara was founded in 2015 by a group of professionals and volunteers concerned about education and community welfare.</p><p>Starting from neighborhood study groups, the foundation now runs education, health, and community empowerment programs in several areas.</p><p>The foundation is officially registered under Notarial Deed No. 12 of 2015 and Ministry of Law and Human Rights Decree No. AHU-0001234.AH.01.04.Tahun 2015.</p>',
            ]);

            Page::create([
                'title' => 'Visi & Misi',
                'title_en' => 'Vision & Mission',
                'slug' => 'visi-misi',
                'content' => '<h2>Visi</h2><p>Mewujudkan masyarakat yang berdaya, sehat, dan berpendidikan.</p><h2>Misi</h2><ul><li>Menyediakan akses pendidikan yang lebih merata.</li><li>Meningkatkan kesadaran dan layanan kesehatan masyarakat.</li><li>Memberdayakan ekonomi masyarakat melalui pelatihan keterampilan.</li><li>Membangun kolaborasi dengan berbagai pihak untuk kebaikan bersama.</li></ul>',
                'content_en' => '<h2>Vision</h2><p>To create an empowered, healthy, and educated society.</p><h2>Mission</h2><ul><li>Provide more equitable access to education.</li><li>Improve public health awareness and services.</li><li>Empower the community economy through skills training.</li><li>Build collaboration with various parties for the common good.</li></ul>',
            ]);

            Page::create([
                'title' => 'Kebijakan Privasi',
                'title_en' => 'Privacy Policy',
                'slug' => 'kebijakan-privasi',
                'content' => '<p>Kami menghormati privasi setiap pengunjung dan donatur. Data yang Anda kirimkan melalui formulir hanya digunakan untuk keperluan komunikasi dan verifikasi donasi.</p><p>Kami tidak membagikan data pribadi kepada pihak ketiga tanpa izin, kecuali diwajibkan oleh hukum.</p>',
                'content_en' => '<p>We respect the privacy of every visitor and donor. Data you submit through our forms is used only for communication and donation verification.</p><p>We do not share personal data with third parties without consent, except as required by law.</p>',
                'is_published' => true,
            ]);
        }

        if (Donation::count() === 0) {
            Donation::create([
                'donor_name' => 'Hamba Allah',
                'email' => 'donatur@example.com',
                'amount' => 500000,
                'method' => 'transfer',
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now()->subDays(3),
                'note' => 'Semoga bermanfaat.',
            ]);

            Donation::create([
                'donor_name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'phone' => '08123456789',
                'amount' => 250000,
                'method' => 'transfer',
                'status' => 'pending',
                'note' => 'Transfer via mobile banking.',
            ]);
        }

        if (Contact::count() === 0) {
            Contact::create([
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'phone' => '081298765432',
                'subject' => 'Informasi program relawan',
                'message' => 'Selamat pagi, saya ingin bertanya mengenai pendaftaran relawan pengajar. Terima kasih.',
            ]);
        }
    }
}
