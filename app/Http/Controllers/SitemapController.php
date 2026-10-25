<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $paths = collect(['', '/about', '/services', '/projects', '/contact'])
            ->merge(Service::query()->where('is_published', true)->pluck('slug')->map(fn ($slug) => "/services/{$slug}"))
            ->merge(Project::query()->where('is_published', true)->pluck('slug')->map(fn ($slug) => "/projects/{$slug}"));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        foreach ($paths as $path) {
            foreach (SetLocale::LOCALES as $locale) {
                $xml .= '<url><loc>'.e(url("/{$locale}{$path}")).'</loc>';
                foreach (SetLocale::LOCALES as $alt) {
                    $xml .= '<xhtml:link rel="alternate" hreflang="'.$alt.'" href="'.e(url("/{$alt}{$path}")).'"/>';
                }
                $xml .= '</url>';
            }
        }

        return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
    }
}
