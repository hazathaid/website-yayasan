<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use App\Models\Program;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = collect();
        $locales = ['id', 'en'];

        foreach ($locales as $locale) {
            $staticRoutes = [
                'home',
                'about',
                'programs.index',
                'posts.index',
                'galleries.index',
                'downloads.index',
                'donations.index',
                'contact.index',
            ];

            foreach ($staticRoutes as $route) {
                $urls->push(['loc' => lroute($route, [], $locale), 'lastmod' => now()]);
            }

            Post::published()->get()->each(fn ($post) => $urls->push([
                'loc' => lroute('posts.show', ['post' => $post], $locale),
                'lastmod' => $post->updated_at,
            ]));

            Program::published()->get()->each(fn ($program) => $urls->push([
                'loc' => lroute('programs.show', ['program' => $program], $locale),
                'lastmod' => $program->updated_at,
            ]));

            Gallery::all()->each(fn ($gallery) => $urls->push([
                'loc' => lroute('galleries.show', ['gallery' => $gallery], $locale),
                'lastmod' => $gallery->updated_at,
            ]));

            Page::published()->get()->each(fn ($page) => $urls->push([
                'loc' => lroute('pages.show', ['page' => $page], $locale),
                'lastmod' => $page->updated_at,
            ]));
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
