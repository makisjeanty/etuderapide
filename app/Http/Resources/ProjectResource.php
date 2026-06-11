<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'status' => $this->status?->value ?? (string) $this->status,
            'featured_image' => $this->featured_image,
            'tech_stack' => $this->tech_stack ?? [],
            'repository_url' => $this->repository_url,
            'demo_url' => $this->demo_url,
            'started_at' => $this->started_at instanceof \DateTimeInterface ? $this->started_at->toDateString() : (is_string($this->started_at) ? substr($this->started_at, 0, 10) : $this->started_at),
            'finished_at' => $this->finished_at instanceof \DateTimeInterface ? $this->finished_at->toDateString() : (is_string($this->finished_at) ? substr($this->finished_at, 0, 10) : $this->finished_at),
            'is_featured' => (bool) $this->is_featured,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'type' => $this->category->type,
            ] : null,
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'email' => array_key_exists('email', $this->author->getAttributes()) ? $this->author->email : null,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
