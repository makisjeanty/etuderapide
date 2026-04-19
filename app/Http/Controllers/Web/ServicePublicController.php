<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ServicePublicController extends Controller
{
    public function index(): View
    {
        try {
            $services = Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->paginate(12);
        } catch (\Exception $e) {
            \Log::error('Falha de banco em Serviços: '.$e->getMessage());
            $services = new LengthAwarePaginator([], 0, 12);
        }

        return view('public.services.index', compact('services'));
    }

    public function show(string $slug): View
    {
        try {
            $service = Service::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
        } catch (\Exception $e) {
            \Log::error('Falha ao buscar serviço: '.$e->getMessage());
            abort(404);
        }

        return view('public.services.show', compact('service'));
    }
}
