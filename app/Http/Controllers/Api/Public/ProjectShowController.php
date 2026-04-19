<?php

namespace App\Http\Controllers\Api\Public;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectShowController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $project = Project::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('slug', $slug)
            ->where('status', ProjectStatus::Published)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $project->id,
                'title' => $project->title,
                'slug' => $project->slug,
                'summary' => $project->summary,
                'description' => $project->description,
                'featured_image' => $project->featured_image,
                'tech_stack' => $project->tech_stack ?? [],
                'demo_url' => $project->demo_url,
                'repository_url' => $project->repository_url,
                'started_at' => $project->started_at?->toDateString(),
                'finished_at' => $project->finished_at?->toDateString(),
                'is_featured' => $project->is_featured,
                'seo_title' => $project->seo_title,
                'seo_description' => $project->seo_description,
                'category' => $project->category ? [
                    'id' => $project->category->id,
                    'name' => $project->category->name,
                    'slug' => $project->category->slug,
                ] : null,
                'author' => $project->author ? [
                    'id' => $project->author->id,
                    'name' => $project->author->name,
                ] : null,
            ],
        ]);
    }
}
