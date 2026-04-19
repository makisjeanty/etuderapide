<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadIndexController extends Controller
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
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')->toString()))
            ->when($request->filled('service_interest'), fn ($builder) => $builder->where('service_interest', $request->string('service_interest')->toString()))
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->when($request->filled('created_from'), fn ($builder) => $builder->whereDate('created_at', '>=', $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString()))
            ->when($request->filled('created_to'), fn ($builder) => $builder->whereDate('created_at', '<=', $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()))
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $leads = $query
            ->paginate($perPage)
            ->withQueryString();

        $data = $leads->getCollection()
            ->map(fn (Lead $lead) => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'service_interest' => $lead->service_interest,
                'status' => $lead->status,
                'quoted_value' => $lead->quoted_value,
                'created_at' => $lead->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
                'count' => $data->count(),
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }
}
