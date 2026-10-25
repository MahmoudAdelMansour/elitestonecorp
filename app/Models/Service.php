<?php

namespace App\Models;

use App\Enums\ServiceGroup;
use App\Models\Concerns\GeneratesSlug;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use GeneratesSlug, HasLocalizedAttributes;

    protected static string $slugSource = 'title_en';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'group' => ServiceGroup::class,
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order');
    }
}
