<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        try {
            $posts = Post::where('is_published', true)->latest('published_at')->get();
            $projects = Project::where('status', ProjectStatus::Published)->latest()->get();
            $services = Service::where('is_active', true)->latest()->get();
        } catch (\Exception $e) {
            \Log::error('Falha ao gerar sitemap: '.$e->getMessage());
            $posts = collect();
            $projects = collect();
            $services = collect();
        }

        return response()->view('public.sitemap', [
            'posts' => $posts,
            'projects' => $projects,
            'services' => $services,
        ])->header('Content-Type', 'text/xml');
    }
}
