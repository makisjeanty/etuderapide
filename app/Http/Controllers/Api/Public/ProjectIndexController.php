<?php

namespace App\Http\Controllers\Api\Public;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $projects = Project::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('status', ProjectStatus::Published)
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', $search)
                        ->orWhere('slug', 'like', $search)
                        ->orWhere('summary', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->when($request->has('is_featured'), fn ($builder) => $builder->where('is_featured', $request->boolean('is_featured')))
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        $data = $projects->getCollection()->map(fn (Project $project) => [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'summary' => $project->summary,
            'featured_image' => $project->featured_image,
            'tech_stack' => $project->tech_stack ?? [],
            'demo_url' => $project->demo_url,
            'repository_url' => $project->repository_url,
            'started_at' => $project->started_at?->toDateString(),
            'finished_at' => $project->finished_at?->toDateString(),
            'is_featured' => $project->is_featured,
            'category' => $project->category ? [
                'id' => $project->category->id,
                'name' => $project->category->name,
                'slug' => $project->category->slug,
            ] : null,
            'author' => $project->author ? [
                'id' => $project->author->id,
                'name' => $project->author->name,
            ] : null,
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'count' => $data->count(),
            ],
        ]);
    }
}
