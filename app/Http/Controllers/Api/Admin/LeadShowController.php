<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadShowController extends Controller
{
    public function __invoke(Request $request, Lead $lead): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageLeads() && $request->user()?->hasVerifiedEmail(),
            403
        );

        return response()->json([
            'data' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'service_interest' => $lead->service_interest,
                'message' => $lead->message,
                'internal_notes' => $lead->internal_notes,
                'payment_link' => $lead->payment_link,
                'quoted_value' => $lead->quoted_value,
                'status' => $lead->status,
                'created_at' => $lead->created_at?->toIso8601String(),
                'updated_at' => $lead->updated_at?->toIso8601String(),
            ],
        ]);
    }
}
