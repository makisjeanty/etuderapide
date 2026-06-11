<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectIndexController extends BaseApiController
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
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->filterByStatus($request->string('status')->toString())
            ->filterByFeatured($request->has('is_featured') ? $request->boolean('is_featured') : null)
            ->filterByDateRange(
                $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString(),
                $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()
            )
            ->filterByFinishedRange(
                $request->date('finished_from')?->toDateString() ?? $request->string('finished_from')->toString(),
                $request->date('finished_to')?->toDateString() ?? $request->string('finished_to')->toString()
            )
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $projects = $query->paginate($perPage)->withQueryString();

        return $this->respondWithPagination($projects, ProjectResource::class, [
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);
    }
}
