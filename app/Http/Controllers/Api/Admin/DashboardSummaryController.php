<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardSummaryController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()?->canViewDashboard() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $totalLeads = Lead::count();
        $qualifiedLeads = Lead::whereIn('status', ['replied', 'archived'])->count();

        return response()->json([
            'data' => [
                'posts_count' => Post::count(),
                'projects_count' => Project::count(),
                'services_count' => Service::count(),
                'total_leads_count' => $totalLeads,
                'new_leads_count' => Lead::where('status', 'new')->count(),
                'total_pipeline_value' => (float) Lead::where('status', 'replied')->sum('quoted_value'),
                'conversion_rate' => $totalLeads > 0
                    ? round(($qualifiedLeads / $totalLeads) * 100, 1)
                    : 0.0,
            ],
        ]);
    }
}
