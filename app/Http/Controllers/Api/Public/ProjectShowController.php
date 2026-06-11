<?php

namespace App\Http\Controllers\Api\Public;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectShowController extends BaseApiController
{
    public function __invoke(string $slug): JsonResponse
    {
        $project = Project::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('slug', $slug)
            ->where('status', ProjectStatus::Published)
            ->firstOrFail();

        return $this->respondWithResource($project, ProjectResource::class);
    }
}
