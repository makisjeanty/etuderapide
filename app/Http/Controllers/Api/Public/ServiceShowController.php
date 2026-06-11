<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceShowController extends BaseApiController
{
    public function __invoke(string $slug): JsonResponse
    {
        $service = Service::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return $this->respondWithResource($service, ServiceResource::class);
    }
}
