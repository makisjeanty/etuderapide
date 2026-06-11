<?php

namespace App\Http\Controllers\Api\Public;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $projects = Project::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('status', ProjectStatus::Published)
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->filterByFeatured($request->has('is_featured') ? $request->boolean('is_featured') : null)
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->paginate($perPage)
            ->withQueryString();

        return $this->respondWithPagination($projects, ProjectResource::class);
    }
}
