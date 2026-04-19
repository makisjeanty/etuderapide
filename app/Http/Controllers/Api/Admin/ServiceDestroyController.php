<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceDestroyController extends Controller
{
    public function __invoke(Request $request, Service $service): JsonResponse
    {
        abort_unless(
            $request->user()?->canManageServices() && $request->user()?->hasVerifiedEmail(),
            403
        );

        $id = $service->id;
        $name = $service->name;
        $service->delete();

        AuditLogger::record($request->user(), 'service.deleted', Service::class, $id, [
            'name' => $name,
        ], $request);

        return response()->json([], 204);
    }
}
