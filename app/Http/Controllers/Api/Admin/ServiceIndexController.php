<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageServices() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $perPage = max(1, min((int) $request->integer('per_page', $request->integer('limit', 10)), 50));
        $sortBy = $request->string('sort_by', 'name')->toString();
        $sortDirection = strtolower($request->string('sort_direction', 'asc')->toString()) === 'desc' ? 'desc' : 'asc';
        $allowedSorts = ['created_at', 'updated_at', 'name', 'price_from'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'name';
        }

        $query = Service::query()
            ->with(['category:id,name,slug,type', 'author:id,name,email'])
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->filterByActive($request->has('is_active') ? $request->boolean('is_active') : null)
            ->filterByDateRange(
                $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString(),
                $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()
            )
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $services = $query->paginate($perPage)->withQueryString();

        return $this->respondWithPagination($services, ServiceResource::class, [
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
        ]);
    }
}
