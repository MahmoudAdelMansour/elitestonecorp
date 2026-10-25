<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Seo
{
    /**
     * Build the meta payload shared with both the Blade root view and the React <Head>.
     *
     * @param  array<int, array<string, mixed>>  $schema
     * @return array<string, mixed>
     */
    public static function make(?string $title = null, ?string $description = null, ?string $image = null, array $schema = []): array
    {
        $brand = __('site.brand');

        return [
            'title' => $title ? Str::limit("{$title} | {$brand}", 70, '') : __('site.meta.home_title'),
            'description' => Str::limit(strip_tags($description ?? __('site.meta.home_description')), 160),
            'image' => $image ? Storage::disk('public')->url($image) : asset('images/brand/logo-badge-navy.jpg'),
            'schema' => $schema,
        ];
    }

    public static function storageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
