<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(): View
    {
        $services = Service::query()->latest()->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'service')->orWhere('type', 'general')->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['is_active'] = $request->has('is_active')
            ? (string) $request->input('is_active') === '1'
            : true;

        $service = Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('status', __('Service created.'));
    }

    public function show(Service $service): View
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        $categories = Category::where('type', 'service')->orWhere('type', 'general')->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();
        if ($request->has('is_active')) {
            $data['is_active'] = (string) $request->input('is_active') === '1';
        } else {
            unset($data['is_active']);
        }

        $service->update($data);
        $service->refresh();

        return redirect()->route('admin.services.index')
            ->with('status', __('Service updated.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('status', __('Service deleted.'));
    }
}
