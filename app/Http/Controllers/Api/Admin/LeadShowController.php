<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadShowController extends BaseApiController
{
    public function __invoke(Request $request, Lead $lead): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageLeads() && $request->user()?->hasVerifiedEmail(),
            403
        );

        return $this->respondWithResource($lead, LeadResource::class);
    }
}
