<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Fills the "slug" column from an English attribute when it is left empty,
 * so admins never have to think about URLs.
 */
trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::saving(function (self $model) {
            if (filled($model->slug)) {
                $model->slug = Str::slug($model->slug);

                return;
            }

            $base = Str::slug((string) $model->getAttribute(static::$slugSource)) ?: Str::lower(Str::random(8));
            $slug = $base;
            $i = 2;
            while (static::query()->where('slug', $slug)->whereKeyNot($model->getKey())->exists()) {
                $slug = "{$base}-".$i++;
            }
            $model->slug = $slug;
        });
    }
}
