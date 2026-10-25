<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->localized('title'),
            'summary' => $this->localized('summary'),
            'year' => $this->year,
            'location' => $this->localized('location'),
            'area' => $this->area,
            'cover' => Seo::storageUrl($this->cover_image),
            'featured' => $this->is_featured,
            'sector' => $this->whenLoaded('sector', fn () => $this->sector ? [
                'slug' => $this->sector->slug,
                'name' => $this->sector->localized('name'),
            ] : null),
            'client' => $this->whenLoaded('client', fn () => $this->client?->name),
            'services' => $this->whenLoaded('services', fn () => $this->services->map(fn ($service) => [
                'slug' => $service->slug,
                'title' => $service->localized('title'),
            ])),
        ];
    }
}
