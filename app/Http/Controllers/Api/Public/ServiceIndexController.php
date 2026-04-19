<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 12), 50));

        $services = Service::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('is_active', true)
            ->when($request->filled('search'), function ($builder) use ($request) {
                $search = '%'.$request->string('search')->toString().'%';

                $builder->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', $search)
                        ->orWhere('slug', 'like', $search)
                        ->orWhere('short_description', 'like', $search);
                });
            })
            ->when($request->filled('category_id'), fn ($builder) => $builder->where('category_id', $request->integer('category_id')))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $data = $services->getCollection()->map(fn (Service $service) => [
            'id' => $service->id,
            'name' => $service->name,
            'slug' => $service->slug,
            'short_description' => $service->short_description,
            'price_from' => $service->price_from,
            'delivery_time' => $service->delivery_time,
            'call_to_action' => $service->call_to_action,
            'featured_image' => $service->featured_image,
            'category' => $service->category ? [
                'id' => $service->category->id,
                'name' => $service->category->name,
                'slug' => $service->category->slug,
            ] : null,
            'author' => $service->author ? [
                'id' => $service->author->id,
                'name' => $service->author->name,
            ] : null,
        ]);

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'count' => $data->count(),
            ],
        ]);
    }
}
