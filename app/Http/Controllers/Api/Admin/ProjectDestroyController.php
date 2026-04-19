<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectDestroyController extends Controller
{
    public function __invoke(Request $request, Project $project): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageProjects() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $id = $project->id;
        $title = $project->title;
        $project->delete();

        AuditLogger::record($request->user(), 'project.deleted', Project::class, $id, [
            'title' => $title,
        ], $request);

        return response()->json([], 204);
    }
}
