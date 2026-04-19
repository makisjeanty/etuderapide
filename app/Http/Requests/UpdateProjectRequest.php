<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $project && $this->user()?->can('update', $project);
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('slug') && $this->filled('title')) {
            $this->merge(['slug' => Str::slug($this->string('title'))]);
        }

        if ($this->has('tech_stack_lines')) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $this->input('tech_stack_lines', ''));
            $stack = array_values(array_filter(array_map('trim', $lines)));
            $this->merge(['tech_stack' => $stack === [] ? null : $stack]);
        }
    }

    public function rules(): array
    {
        /** @var Project $project */
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('projects', 'slug')->ignore($project->id),
            ],
            'summary' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:65535'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'tech_stack_lines' => ['nullable', 'string', 'max:8000'],
            'tech_stack' => ['nullable', 'array'],
            'tech_stack.*' => ['string', 'max:120'],
            'repository_url' => ['nullable', 'url', 'max:2048'],
            'demo_url' => ['nullable', 'url', 'max:2048'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'is_featured' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
