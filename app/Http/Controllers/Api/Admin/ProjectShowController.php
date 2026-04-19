<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectShowController extends Controller
{
    public function __invoke(Request $request, Project $project): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageProjects() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $project->loadMissing(['category:id,name,slug,type', 'author:id,name,email']);

        return response()->json([
            'data' => ProjectIndexController::serializeProject($project) + [
                'description' => $project->description,
            ],
        ]);
    }
}
