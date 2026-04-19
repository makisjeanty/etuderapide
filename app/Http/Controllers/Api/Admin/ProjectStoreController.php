<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProjectStoreController extends Controller
{
    public function __invoke(StoreProjectRequest $request): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $data = Arr::except($request->validated(), ['tech_stack_lines']);
        $data['user_id'] = $request->user()->id;
        $data['is_featured'] = $request->boolean('is_featured');

        $project = Project::create($data);
        $project->load(['category:id,name,slug,type', 'author:id,name,email']);

        AuditLogger::record($request->user(), 'project.created', $project::class, $project->id, [
            'title' => $project->title,
            'status' => $project->status->value,
        ], $request);

        if ($project->status === ProjectStatus::Published) {
            AuditLogger::record($request->user(), 'project.published', $project::class, $project->id, [
                'title' => $project->title,
            ], $request);
        }

        return response()->json([
            'data' => ProjectIndexController::serializeProject($project) + [
                'description' => $project->description,
            ],
        ], 201);
    }
}
