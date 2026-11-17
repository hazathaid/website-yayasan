<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SitemapController;
use App\Models\Donation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

$publicRoutes = function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/tentang', [PageController::class, 'about'])->name('about');

    Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/program/{program}', [ProgramController::class, 'show'])->name('programs.show');

    Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
    Route::get('/berita/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/galeri/{gallery}', [GalleryController::class, 'show'])->name('galleries.show');

    Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads.index');

    Route::get('/donasi', [DonationController::class, 'index'])->name('donations.index');
    Route::post('/donasi', [DonationController::class, 'store'])->middleware('throttle:10,1')->name('donations.store');

    Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

    Route::get('/halaman/{page}', [PageController::class, 'show'])->name('pages.show');
};

Route::middleware('locale')->group($publicRoutes);

Route::prefix('en')->name('en.')->middleware('locale')->group($publicRoutes);

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: ".url('/sitemap.xml')."\n",
        200,
        ['Content-Type' => 'text/plain'],
    );
})->name('robots');

Route::get('/admin/donations/{donation}/proof', function (Donation $donation) {
    abort_unless(auth()->check(), 403);
    abort_unless($donation->proof_path && Storage::disk('local')->exists($donation->proof_path), 404);

    return Storage::disk('local')->download($donation->proof_path);
})->name('donations.proof');
