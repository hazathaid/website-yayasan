<?php

namespace App\Http\Controllers;

use App\Models\Download;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::published()
            ->orderBy('sort_order')
            ->latest('created_at')
            ->paginate(12);

        return view('downloads.index', compact('downloads'));
    }
}
