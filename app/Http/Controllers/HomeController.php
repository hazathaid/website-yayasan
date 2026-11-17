<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Program;

class HomeController extends Controller
{
    public function __invoke()
    {
        $posts = Post::published()->with('category')->latest('published_at')->take(3)->get();
        $programs = Program::published()->where('status', 'active')->latest()->take(3)->get();
        $galleries = Gallery::withCount('items')->latest()->take(2)->get();

        $stats = [
            'programs' => Program::published()->count(),
            'posts' => Post::published()->count(),
            'donors' => Donation::verified()->count(),
            'donations' => Donation::verified()->sum('amount'),
        ];

        return view('home', compact('posts', 'programs', 'galleries', 'stats'));
    }
}
