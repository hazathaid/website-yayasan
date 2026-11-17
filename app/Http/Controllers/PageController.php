<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\TeamMember;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::published()->where('slug', 'tentang-kami')->first();
        $visiMisi = Page::published()->where('slug', 'visi-misi')->first();
        $team = TeamMember::orderBy('sort_order')->get();

        return view('about', compact('page', 'visiMisi', 'team'));
    }

    public function show(Page $page)
    {
        abort_unless($page->is_published, 404);

        return view('pages.show', compact('page'));
    }
}
