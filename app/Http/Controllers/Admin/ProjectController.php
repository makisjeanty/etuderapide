<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(): View
    {
        $projects = Project::query()
            ->latest()
            ->paginate(15);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $categories = Category::where('type', 'project')
            ->orWhere('type', 'general')
            ->get();

        return view('admin.projects.create', compact('categories'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = Arr::except($request->validated(), ['tech_stack_lines']);
        $data['user_id'] = $request->user()->id;
        $data['is_featured'] = $request->boolean('is_featured');

        $project = Project::create($data);

        return redirect()->route('admin.projects.index')
            ->with('status', __('Project created.'));
    }

    public function show(Project $project): View
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $categories = Category::where('type', 'project')
            ->orWhere('type', 'general')
            ->get();

        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = Arr::except($request->validated(), ['tech_stack_lines']);
        $data['is_featured'] = $request->boolean('is_featured');

        $project->update($data);
        $project->refresh();

        return redirect()->route('admin.projects.index')
            ->with('status', __('Project updated.'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('status', __('Project deleted.'));
    }
}
