<?php

namespace Tests\Feature;

use App\Mail\ContactSubmitted;
use App\Mail\DonationSubmitted;
use App\Models\Contact;
use App\Models\Donation;
use App\Models\Download;
use App\Models\Page;
use App\Models\Post;
use App\Models\Program;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_homepage_loads(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Yayasan Cahaya Nusantara');
    }

    public function test_public_pages_load(): void
    {
        $post = Post::published()->firstOrFail();
        $program = Program::published()->firstOrFail();

        $this->get(route('about'))->assertOk();
        $this->get(route('programs.index'))->assertOk();
        $this->get(route('programs.show', $program))->assertOk();
        $this->get(route('posts.index'))->assertOk();
        $this->get(route('posts.index', ['q' => 'beasiswa', 'kategori' => 'kegiatan']))->assertOk();
        $this->get(route('posts.show', $post))->assertOk();
        $this->get(route('galleries.index'))->assertOk();
        $this->get(route('downloads.index'))->assertOk();
        $this->get(route('donations.index'))->assertOk();
        $this->get(route('contact.index'))->assertOk()->assertSee('Media Sosial');
        $this->get(route('pages.show', Page::where('slug', 'visi-misi')->firstOrFail()))->assertOk();
        $this->get(route('pages.show', Page::where('slug', 'kebijakan-privasi')->firstOrFail()))->assertOk();
    }

    public function test_sitemap_is_valid_xml(): void
    {
        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset', false)
            ->assertSee('/en', false);
    }

    public function test_english_pages_load_with_translated_content(): void
    {
        $post = Post::published()->firstOrFail();
        $program = Program::published()->firstOrFail();

        $this->get('/en')->assertOk()->assertSee('Latest News');
        $this->get('/en/tentang')->assertOk()->assertSee('Organization Structure');
        $this->get('/en/program')->assertOk()->assertSee('Ongoing');
        $this->get('/en/program/'.$program->slug)->assertOk();
        $this->get('/en/berita')->assertOk()->assertSee('Search news...');
        $this->get('/en/berita/'.$post->slug)->assertOk()->assertSee($post->title_en);
        $this->get('/en/galeri')->assertOk()->assertSee('Activity documentation');
        $this->get('/en/unduhan')->assertOk()->assertSee('Documents & reports');
        $this->get('/en/donasi')->assertOk()->assertSee('Confirm Donation');
        $this->get('/en/kontak')->assertOk()->assertSee('Contact us');
        $this->get('/en/halaman/visi-misi')->assertOk()->assertSee('Vision');
    }

    public function test_layout_contains_hreflang_alternates(): void
    {
        $response = $this->get('/en/berita')->assertOk();

        $response->assertSee('hreflang="id"', false);
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="x-default"', false);
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin', false)
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false)
            ->assertDontSee('{{', false);
    }

    public function test_post_uses_custom_meta_title_and_og_image(): void
    {
        $post = Post::published()->firstOrFail();
        $post->update([
            'meta_title' => 'Judul SEO Khusus',
            'meta_description' => 'Deskripsi SEO khusus.',
            'cover_path' => 'posts/cover.jpg',
        ]);

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('<title>Judul SEO Khusus</title>', false)
            ->assertSee('<meta name="description" content="Deskripsi SEO khusus.">', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('posts/cover.jpg', false);
    }

    public function test_page_uses_custom_meta_title(): void
    {
        $page = Page::where('slug', 'visi-misi')->firstOrFail();
        $page->update(['meta_title' => 'Judul SEO Halaman']);

        $this->get(route('pages.show', $page))
            ->assertOk()
            ->assertSee('<title>Judul SEO Halaman</title>', false);
    }

    public function test_contact_form_stores_message_and_notifies_admin(): void
    {
        Mail::fake();

        $this->post(route('contact.store'), [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Tanya program',
            'message' => 'Saya ingin bertanya tentang program beasiswa.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contacts', ['email' => 'budi@example.com']);
        Mail::assertSent(ContactSubmitted::class);
    }

    public function test_contact_submission_still_succeeds_when_notification_fails(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('mail server down'));

        $this->post(route('contact.store'), [
            'name' => 'Budi',
            'email' => 'budi-gagal@example.com',
            'subject' => 'Tanya program',
            'message' => 'Saya ingin bertanya tentang program beasiswa.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', ['email' => 'budi-gagal@example.com']);
    }

    public function test_donation_confirmation_stores_proof_and_notifies_admin(): void
    {
        Storage::fake('local');
        Mail::fake();

        $program = Program::published()->firstOrFail();

        $this->post(route('donations.store'), [
            'program_id' => $program->id,
            'donor_name' => 'Siti Uji',
            'email' => 'siti-uji@example.com',
            'amount' => 250000,
            'note' => 'Semoga bermanfaat',
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect();

        $donation = Donation::where('email', 'siti-uji@example.com')->firstOrFail();

        $this->assertSame('pending', $donation->status);
        $this->assertSame($program->id, $donation->program_id);
        Storage::disk('local')->assertExists($donation->proof_path);
        Mail::assertSent(DonationSubmitted::class);
    }

    public function test_donation_confirmation_still_succeeds_when_notification_fails(): void
    {
        Storage::fake('local');
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('mail server down'));

        $this->post(route('donations.store'), [
            'donor_name' => 'Siti Gagal',
            'email' => 'siti-gagal@example.com',
            'amount' => 50000,
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('donations', ['email' => 'siti-gagal@example.com']);
    }

    public function test_verifying_donation_updates_linked_program_total(): void
    {
        $program = Program::published()->firstOrFail();
        $before = (float) $program->collected_amount;

        $donation = Donation::create([
            'program_id' => $program->id,
            'donor_name' => 'Donatur Uji',
            'email' => 'donatur-uji@example.com',
            'amount' => 100000,
            'method' => 'transfer',
            'status' => 'pending',
        ]);

        $donation->update(['status' => 'verified', 'verified_at' => now()]);
        $this->assertSame($before + 100000, (float) $program->fresh()->collected_amount);

        $donation->update(['status' => 'rejected', 'verified_at' => now()]);
        $this->assertSame($before, (float) $program->fresh()->collected_amount);
    }

    public function test_downloads_page_lists_published_documents(): void
    {
        Download::create([
            'title' => 'Laporan Tahunan 2025',
            'description' => 'Laporan resmi yayasan.',
            'file_path' => 'downloads/laporan-2025.pdf',
        ]);

        $this->get(route('downloads.index'))
            ->assertOk()
            ->assertSee('Laporan Tahunan 2025')
            ->assertSee('downloads/laporan-2025.pdf', false);
    }

    public function test_tag_filter_shows_tagged_posts(): void
    {
        $post = Post::published()->firstOrFail();
        $tag = Tag::create(['name' => 'Uji Tag', 'slug' => 'uji-tag']);
        $post->tags()->sync([$tag->id]);

        $this->get(route('posts.index', ['tag' => 'uji-tag']))
            ->assertOk()
            ->assertSee($post->title);
    }

    public function test_contact_honeypot_is_ignored(): void
    {
        $this->post(route('contact.store'), [
            'website' => 'http://spam.example',
            'name' => 'Spam',
            'email' => 'spam@example.com',
            'subject' => 'Spam',
            'message' => 'Ini pesan spam yang panjang.',
        ])->assertRedirect();

        $this->assertSame(0, Contact::where('email', 'spam@example.com')->count());
    }

    public function test_admin_panel_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@yayasan.test')->firstOrFail();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }
}
