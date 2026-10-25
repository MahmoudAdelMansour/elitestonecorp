<?php

namespace App\Http\Resources;

use App\Models\Sector;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Sector */
class SectorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->localized('name'),
            'description' => $this->localized('description'),
            'icon' => $this->icon,
            'image' => Seo::storageUrl($this->image),
            'projectsCount' => $this->whenCounted('projects'),
        ];
    }
}
