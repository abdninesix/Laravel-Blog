<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category', 'comments.user'])->withCount('comments')->latest()->paginate(10);
        $categories = Category::all();
        return Inertia::render('admin/posts', ['categories' => $categories, 'posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        if ($request->filled('published_at')) {
            $validated['published_at'] = Carbon::parse($request->published_at)->format('Y-m-d H:i:s');
        }

        $validated['slug'] = Str::slug($request->title);
        
        Post::create($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'nullable|date',
        ]);

        if ($request->filled('published_at')) {
            $validated['published_at'] = Carbon::parse($request->published_at)->format('Y-m-d H:i:s');
        }

        $validated['slug'] = Str::slug($request->title);
        
        $post->update($validated);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Post deleted');
    }
}
