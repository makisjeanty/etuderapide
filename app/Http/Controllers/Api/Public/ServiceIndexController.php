<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceIndexController extends BaseApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $services = Service::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('is_active', true)
            ->filterBySearch($request->string('search')->toString())
            ->filterByCategory($request->integer('category_id') ?: null)
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return $this->respondWithPagination($services, ServiceResource::class);
    }
}
