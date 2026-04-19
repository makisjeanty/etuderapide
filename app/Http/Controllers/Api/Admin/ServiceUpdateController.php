<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;

class ServiceUpdateController extends Controller
{
    public function __invoke(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        abort_unless($request->user()?->hasVerifiedEmail(), 403);

        $wasActive = $service->is_active;
        $data = $request->validated();

        if ($request->has('is_active')) {
            $data['is_active'] = (string) $request->input('is_active') === '1';
        } else {
            unset($data['is_active']);
        }

        $service->update($data);
        $service->refresh()->load(['category:id,name,slug,type', 'author:id,name,email']);

        AuditLogger::record($request->user(), 'service.updated', $service::class, $service->id, [
            'name' => $service->name,
            'is_active' => $service->is_active,
        ], $request);

        if (! $wasActive && $service->is_active) {
            AuditLogger::record($request->user(), 'service.activated', $service::class, $service->id, [
                'name' => $service->name,
            ], $request);
        }

        if ($wasActive && ! $service->is_active) {
            AuditLogger::record($request->user(), 'service.deactivated', $service::class, $service->id, [
                'name' => $service->name,
            ], $request);
        }

        return response()->json([
            'data' => ServiceIndexController::serializeService($service) + [
                'full_description' => $service->full_description,
            ],
        ]);
    }
}
