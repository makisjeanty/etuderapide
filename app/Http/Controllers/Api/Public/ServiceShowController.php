<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceShowController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $service = Service::query()
            ->with(['category:id,name,slug,type', 'author:id,name'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
                'short_description' => $service->short_description,
                'full_description' => $service->full_description,
                'price_from' => $service->price_from,
                'delivery_time' => $service->delivery_time,
                'call_to_action' => $service->call_to_action,
                'featured_image' => $service->featured_image,
                'seo_title' => $service->seo_title,
                'seo_description' => $service->seo_description,
                'category' => $service->category ? [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'slug' => $service->category->slug,
                ] : null,
                'author' => $service->author ? [
                    'id' => $service->author->id,
                    'name' => $service->author->name,
                ] : null,
            ],
        ]);
    }
}
