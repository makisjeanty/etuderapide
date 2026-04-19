<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProjectPublicController extends Controller
{
    public function index(): View
    {
        try {
            $projects = Project::query()
                ->where('status', ProjectStatus::Published)
                ->orderByDesc('is_featured')
                ->latest('updated_at')
                ->paginate(12);
        } catch (\Exception $e) {
            \Log::error('Falha de banco em Projetos: '.$e->getMessage());
            $projects = new LengthAwarePaginator([], 0, 12);
        }

        return view('public.projects.index', compact('projects'));
    }

    public function show(string $slug): View
    {
        try {
            $project = Project::query()
                ->where('slug', $slug)
                ->where('status', ProjectStatus::Published)
                ->firstOrFail();
        } catch (\Exception $e) {
            \Log::error('Falha ao buscar projeto: '.$e->getMessage());
            abort(404);
        }

        return view('public.projects.show', compact('project'));
    }
}
