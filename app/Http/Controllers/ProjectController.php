<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\SectorResource;
use App\Http\Resources\ServiceResource;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $sector = $request->string('sector')->toString() ?: null;
        $service = $request->string('service')->toString() ?: null;

        $projects = Project::query()->published()
            ->with(['sector', 'client', 'services'])
            ->when($sector, fn ($q) => $q->whereRelation('sector', 'slug', $sector))
            ->when($service, fn ($q) => $q->whereRelation('services', 'slug', $service))
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => ProjectResource::collection($projects)->resolve(),
            'sectors' => SectorResource::collection(Sector::query()->published()->get())->resolve(),
            'services' => ServiceResource::collection(Service::query()->published()->get())->resolve(),
            'filters' => ['sector' => $sector, 'service' => $service],
            'meta' => Seo::make(__('site.projects.page_title'), __('site.projects.page_subtitle')),
        ]);
    }

    public function show(Project $project): Response
    {
        abort_unless($project->is_published, 404);

        $project->load(['sector', 'client', 'services']);

        $related = Project::query()->published()
            ->whereKeyNot($project->id)
            ->where('sector_id', $project->sector_id)
            ->with(['sector', 'client'])
            ->limit(3)
            ->get();

        return Inertia::render('Projects/Show', [
            'project' => [
                ...(new ProjectResource($project))->resolve(),
                'challenge' => $project->localized('challenge'),
                'solution' => $project->localized('solution'),
                'gallery' => collect($project->gallery ?? [])->map(fn (string $path) => Seo::storageUrl($path))->values(),
            ],
            'related' => ProjectResource::collection($related)->resolve(),
            'meta' => Seo::make($project->localized('title'), $project->localized('summary'), $project->cover_image),
        ]);
    }
}
