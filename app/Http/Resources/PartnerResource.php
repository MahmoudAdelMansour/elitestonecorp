<?php

namespace App\Http\Resources;

use App\Models\Partner;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Partner */
class PartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => Seo::storageUrl($this->logo),
            'website' => $this->website,
        ];
    }
}
