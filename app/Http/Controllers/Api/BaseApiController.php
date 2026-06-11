<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseApiController extends Controller
{
    /**
     * Respond with a standard paginated response.
     */
    protected function respondWithPagination(LengthAwarePaginator $paginator, string $resourceClass, array $additionalMeta = []): JsonResponse
    {
        $collection = $paginator->getCollection();
        $data = $resourceClass::collection($collection);

        $meta = array_merge([
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'count' => $collection->count(),
        ], $additionalMeta);

        return response()->json([
            'data' => $data->resolve(),
            'meta' => $meta,
        ]);
    }

    /**
     * Respond with a single resource.
     *
     * @param  mixed  $model
     */
    protected function respondWithResource($model, string $resourceClass, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => (new $resourceClass($model))->resolve(),
        ], $status);
    }
}
