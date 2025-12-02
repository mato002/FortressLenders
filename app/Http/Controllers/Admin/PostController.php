<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with('author')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $post = new Post([
            'is_published' => false,
            'published_at' => now(),
        ]);

        return view('admin.posts.create', compact('post'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['author_id'] = auth()->id();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        $post = Post::create($data);
        $this->handleImageUpload($request, $post);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        $post->load('author');
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validatedData($request, $post);
        
        if (empty($data['slug']) || ($post->title !== $data['title'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        $post->update($data);
        $this->handleImageUpload($request, $post);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image_path) {
            Storage::disk('public')->delete($post->featured_image_path);
        }

        $post->delete();

        return back()->with('status', 'Post deleted successfully.');
    }

    protected function validatedData(Request $request, ?Post $post = null): array
    {
        $slugRule = $post 
            ? ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id]
            : ['nullable', 'string', 'max:255', 'unique:posts,slug'];

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => $slugRule,
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        
        if (empty($validated['published_at']) && $validated['is_published']) {
            $validated['published_at'] = now();
        }

        return $validated;
    }

    protected function handleImageUpload(Request $request, Post $post): void
    {
        if (!$request->hasFile('featured_image')) {
            return;
        }

        if ($post->featured_image_path) {
            Storage::disk('public')->delete($post->featured_image_path);
        }

        $path = $request->file('featured_image')->store('posts', 'public');
        $post->update(['featured_image_path' => $path]);
    }
}

