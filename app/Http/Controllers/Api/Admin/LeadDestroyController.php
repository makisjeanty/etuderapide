<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadDestroyController extends Controller
{
    public function __invoke(Request $request, Lead $lead): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageLeads() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $lead->delete();

        return response()->json([], 204);
    }
}
