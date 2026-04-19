<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        try {
            $posts = Post::query()
                ->where('is_published', true)
                ->with(['category', 'author'])
                ->latest('published_at')
                ->paginate(12);
        } catch (\Exception $e) {
            \Log::error('Falha de banco no Blog: '.$e->getMessage());
            $posts = new LengthAwarePaginator([], 0, 12);
        }

        return view('public.blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        try {
            $post = Post::query()
                ->where('slug', $slug)
                ->where('is_published', true)
                ->with(['category', 'author', 'tags'])
                ->firstOrFail();
        } catch (\Exception $e) {
            \Log::error('Falha ao buscar post: '.$e->getMessage());
            abort(404);
        }

        return view('public.blog.show', compact('post'));
    }
}
