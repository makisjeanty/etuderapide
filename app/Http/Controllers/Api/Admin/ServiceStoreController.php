<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;

class ServiceStoreController extends Controller
{
    public function __invoke(StoreServiceRequest $request): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_active'] = $request->has('is_active')
            ? (string) $request->input('is_active') === '1'
            : true;

        $service = Service::create($data);
        $service->load(['category:id,name,slug,type', 'author:id,name,email']);

        AuditLogger::record($request->user(), 'service.created', $service::class, $service->id, [
            'name' => $service->name,
            'is_active' => $service->is_active,
        ], $request);

        if ($service->is_active) {
            AuditLogger::record($request->user(), 'service.activated', $service::class, $service->id, [
                'name' => $service->name,
            ], $request);
        }

        return response()->json([
            'data' => ServiceIndexController::serializeService($service) + [
                'full_description' => $service->full_description,
            ],
        ], 201);
    }
}
