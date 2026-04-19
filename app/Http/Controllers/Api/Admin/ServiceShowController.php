<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceShowController extends Controller
{
    public function __invoke(Request $request, Service $service): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageServices() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $service->loadMissing(['category:id,name,slug,type', 'author:id,name,email']);

        return response()->json([
            'data' => ServiceIndexController::serializeService($service) + [
                'full_description' => $service->full_description,
            ],
        ]);
    }
}
