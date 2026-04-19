<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class ProjectUpdateController extends Controller
{
    public function __invoke(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $wasPublished = $project->status === ProjectStatus::Published;
        $data = Arr::except($request->validated(), ['tech_stack_lines']);
        $data['is_featured'] = $request->boolean('is_featured');

        $project->update($data);
        $project->refresh()->load(['category:id,name,slug,type', 'author:id,name,email']);

        AuditLogger::record($request->user(), 'project.updated', $project::class, $project->id, [
            'title' => $project->title,
            'status' => $project->status->value,
        ], $request);

        if (! $wasPublished && $project->status === ProjectStatus::Published) {
            AuditLogger::record($request->user(), 'project.published', $project::class, $project->id, [
                'title' => $project->title,
            ], $request);
        }

        return response()->json([
            'data' => ProjectIndexController::serializeProject($project) + [
                'description' => $project->description,
            ],
        ]);
    }
}
