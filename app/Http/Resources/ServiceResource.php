<?php

namespace App\Http\Resources;

use App\Models\Service;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Service */
class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'group' => $this->group->value,
            'title' => $this->localized('title'),
            'excerpt' => $this->localized('excerpt'),
            'icon' => $this->icon,
            'image' => Seo::storageUrl($this->image),
        ];
    }
}
