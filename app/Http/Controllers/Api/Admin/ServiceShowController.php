<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceShowController extends BaseApiController
{
    public function __invoke(Request $request, Service $service): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageServices() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $service->loadMissing(['category:id,name,slug,type', 'author:id,name,email']);

        return $this->respondWithResource($service, ServiceResource::class);
    }
}
