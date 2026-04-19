<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadUpdateController extends Controller
{
    public function __invoke(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $lead->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $lead->id,
                'status' => $lead->status,
                'internal_notes' => $lead->internal_notes,
                'payment_link' => $lead->payment_link,
                'quoted_value' => $lead->quoted_value,
                'updated_at' => $lead->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
