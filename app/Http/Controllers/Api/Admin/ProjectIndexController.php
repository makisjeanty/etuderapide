<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageProjects() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $perPage = max(1, min((int) $request->integer('per_page', $request->integer('limit', 10)), 50));
        $sortBy = $request->string('sort_by', 'updated_at')->toString();
        $sortDirection = strtolower($request->string('sort_direction', 'desc')->toString()) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['created_at', 'updated_at', 'title', 'status', 'finished_at'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'updated_at';
        }

        $query = Project::query()
            ->with(['category:id,name,slug,type', 'author:id,name,email'])
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')->toString()))
            ->when($request->has('is_featured'), fn ($builder) => $builder->where('is_featured', $request->boolean('is_featured')))
            ->when($request->filled('created_from'), fn ($builder) => $builder->whereDate('created_at', '>=', $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString()))
            ->when($request->filled('created_to'), fn ($builder) => $builder->whereDate('created_at', '<=', $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()))
            ->when($request->filled('finished_from'), fn ($builder) => $builder->whereDate('finished_at', '>=', $request->date('finished_from')?->toDateString() ?? $request->string('finished_from')->toString()))
            ->when($request->filled('finished_to'), fn ($builder) => $builder->whereDate('finished_at', '<=', $request->date('finished_to')?->toDateString() ?? $request->string('finished_to')->toString()))
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $projects = $query->paginate($perPage)->withQueryString();
        $data = $projects->getCollection()->map(fn (Project $project) => $this->serializeProject($project));

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'count' => $data->count(),
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }

    public static function serializeProject(Project $project): array
    {
        return [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'summary' => $project->summary,
            'status' => $project->status?->value ?? (string) $project->status,
            'featured_image' => $project->featured_image,
            'tech_stack' => $project->tech_stack ?? [],
            'repository_url' => $project->repository_url,
            'demo_url' => $project->demo_url,
            'started_at' => $project->started_at?->toDateString(),
            'finished_at' => $project->finished_at?->toDateString(),
            'is_featured' => $project->is_featured,
            'seo_title' => $project->seo_title,
            'seo_description' => $project->seo_description,
            'category' => $project->category ? [
                'id' => $project->category->id,
                'name' => $project->category->name,
                'slug' => $project->category->slug,
                'type' => $project->category->type,
            ] : null,
            'author' => $project->author ? [
                'id' => $project->author->id,
                'name' => $project->author->name,
                'email' => $project->author->email,
            ] : null,
            'created_at' => $project->created_at?->toIso8601String(),
            'updated_at' => $project->updated_at?->toIso8601String(),
        ];
    }
}
