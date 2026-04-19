<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceIndexController extends Controller
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
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->when($request->has('is_active'), fn ($builder) => $builder->where('is_active', $request->boolean('is_active')))
            ->when($request->filled('created_from'), fn ($builder) => $builder->whereDate('created_at', '>=', $request->date('created_from')?->toDateString() ?? $request->string('created_from')->toString()))
            ->when($request->filled('created_to'), fn ($builder) => $builder->whereDate('created_at', '<=', $request->date('created_to')?->toDateString() ?? $request->string('created_to')->toString()))
            ->orderBy($sortBy, $sortDirection)
            ->orderByDesc('id');

        $services = $query->paginate($perPage)->withQueryString();
        $data = $services->getCollection()->map(fn (Service $service) => $this->serializeService($service));

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'count' => $data->count(),
                'sort_by' => $sortBy,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }

    public static function serializeService(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'slug' => $service->slug,
            'short_description' => $service->short_description,
            'price_from' => $service->price_from,
            'delivery_time' => $service->delivery_time,
            'is_active' => $service->is_active,
            'call_to_action' => $service->call_to_action,
            'featured_image' => $service->featured_image,
            'seo_title' => $service->seo_title,
            'seo_description' => $service->seo_description,
            'category' => $service->category ? [
                'id' => $service->category->id,
                'name' => $service->category->name,
                'slug' => $service->category->slug,
                'type' => $service->category->type,
            ] : null,
            'author' => $service->author ? [
                'id' => $service->author->id,
                'name' => $service->author->name,
                'email' => $service->author->email,
            ] : null,
            'created_at' => $service->created_at?->toIso8601String(),
            'updated_at' => $service->updated_at?->toIso8601String(),
        ];
    }
}
