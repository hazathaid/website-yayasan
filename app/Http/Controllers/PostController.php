<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with(['category', 'tags'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q
                    ->where('title', 'like', $term)
                    ->orWhere('title_en', 'like', $term)
                    ->orWhere('excerpt', 'like', $term)
                    ->orWhere('excerpt_en', 'like', $term)
                    ->orWhereHas('tags', fn ($tag) => $tag->where('name', 'like', $term)->orWhere('name_en', 'like', $term))
                );
            })
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('kategori')));
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $request->string('tag')));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->loadMissing('tags');

        $related = Post::published()
            ->whereKeyNot($post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }
}
