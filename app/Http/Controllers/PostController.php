<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        if (!$post->is_published || ($post->published_at && $post->published_at->isFuture())) {
            abort(404);
        }

        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('posts.show', compact('post', 'recentPosts'));
    }
}
