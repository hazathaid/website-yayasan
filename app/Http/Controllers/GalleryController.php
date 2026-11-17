<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::withCount('items')->latest()->paginate(9);

        return view('galleries.index', compact('galleries'));
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('items');

        return view('galleries.show', compact('gallery'));
    }
}
