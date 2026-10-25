<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Support\Seo;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Services/Index', [
            'services' => ServiceResource::collection(Service::query()->published()->get())->resolve(),
            'meta' => Seo::make(__('site.services.page_title'), __('site.services.page_subtitle')),
        ]);
    }

    public function show(Service $service): Response
    {
        abort_unless($service->is_published, 404);

        $projects = $service->projects()->published()->with(['sector', 'client'])->limit(3)->get();

        return Inertia::render('Services/Show', [
            'service' => [
                ...(new ServiceResource($service))->resolve(),
                'body' => $service->localized('body'),
                'features' => $service->localizedLines('features'),
            ],
            'projects' => ProjectResource::collection($projects)->resolve(),
            'others' => ServiceResource::collection(
                Service::query()->published()->whereKeyNot($service->id)->get()
            )->resolve(),
            'meta' => Seo::make($service->localized('title'), $service->localized('excerpt'), $service->image, [[
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $service->localized('title'),
                'description' => $service->localized('excerpt'),
                'provider' => ['@type' => 'GeneralContractor', 'name' => 'Elite Stone'],
                'areaServed' => 'Egypt',
            ]]),
        ]);
    }
}
