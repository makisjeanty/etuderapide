<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageLeads() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $perPage = max(1, min((int) $request->integer('per_page', $request->integer('limit', 10)), 50));
        $sortBy = $request->string('sort_by', 'created_at')->toString();
        $sortDirection = strtolower($request->string('sort_direction', 'desc')->toString()) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['created_at', 'name', 'status', 'quoted_value'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $query = Lead::query()
            ->filterByStatus($request->string('status')->toString())
            ->filterByServiceInterest($request->string('service_interest')->toString())
            ->filterBySearch($request->string('search')->toString())
            ->filterByDateRange(
                $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString(),
                $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()
            )
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $leads = $query->paginate($perPage)->withQueryString();

        return $this->respondWithPagination($leads, LeadResource::class, [
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);
    }
}
