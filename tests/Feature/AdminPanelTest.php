<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_admin_resource_pages_load(): void
    {
        $admin = User::where('email', 'admin@yayasan.test')->firstOrFail();

        $routes = [
            '/admin',
            '/admin/categories',
            '/admin/categories/create',
            '/admin/posts',
            '/admin/posts/create',
            '/admin/programs',
            '/admin/programs/create',
            '/admin/galleries',
            '/admin/galleries/create',
            '/admin/tags',
            '/admin/tags/create',
            '/admin/downloads',
            '/admin/downloads/create',
            '/admin/donations',
            '/admin/donations/create',
            '/admin/contacts',
            '/admin/team-members',
            '/admin/team-members/create',
            '/admin/pages',
            '/admin/pages/create',
            '/admin/users',
            '/admin/users/create',
            '/admin/manage-settings',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get($route)->assertOk();
        }
    }

    public function test_editor_can_access_admin_but_not_users(): void
    {
        $editor = User::where('email', 'editor@yayasan.test')->firstOrFail();

        $this->actingAs($editor)->get('/admin')->assertOk();
        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
    }

    public function test_only_authenticated_admin_can_download_proof(): void
    {
        Storage::fake('local');

        $admin = User::where('email', 'admin@yayasan.test')->firstOrFail();
        $donation = Donation::firstOrFail();
        $donation->update(['proof_path' => 'donations/bukti.jpg']);
        Storage::disk('local')->put('donations/bukti.jpg', 'bukti');

        $this->get(route('donations.proof', $donation))->assertForbidden();
        $this->actingAs($admin)->get(route('donations.proof', $donation))->assertOk();
    }
}
