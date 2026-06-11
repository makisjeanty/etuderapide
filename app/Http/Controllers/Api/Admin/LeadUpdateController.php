<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadUpdateController extends BaseApiController
{
    public function __invoke(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $lead->update($request->validated());

        return $this->respondWithResource($lead, LeadResource::class);
    }
}
