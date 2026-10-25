<?php

namespace App\Http\Middleware;

use App\Models\Service;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            ...$this->siteProps($request),
        ];
    }

    /**
     * Props every public page needs. Also used by the error page renderer,
     * which runs without the web middleware when no route matched.
     *
     * @return array<string, mixed>
     */
    public function siteProps(Request $request): array
    {
        // Resolved lazily: the route-level SetLocale middleware runs after share().
        $locale = fn (): string => app()->getLocale();

        return [
            'locale' => $locale,
            'dir' => fn () => $locale() === 'ar' ? 'rtl' : 'ltr',
            'alternateUrl' => fn () => '/'.($locale() === 'ar' ? 'en' : 'ar').Str::after($request->getPathInfo(), '/'.$locale()),
            'url' => $request->url(),
            't' => fn () => trans('site'),
            'site' => function () use ($locale) {
                $settings = Setting::values();

                return [
                    'phone' => $settings['phone'] ?? null,
                    'whatsapp' => $settings['whatsapp'] ?? null,
                    'email' => $settings['email'] ?? null,
                    'address' => $settings['address_'.$locale()] ?? null,
                    'workingHours' => $settings['working_hours_'.$locale()] ?? null,
                    'mapQuery' => $settings['map_query'] ?? null,
                    'facebook' => $settings['facebook'] ?? null,
                    'instagram' => $settings['instagram'] ?? null,
                    'linkedin' => $settings['linkedin'] ?? null,
                    'foundedYear' => (int) ($settings['founded_year'] ?? 2019),
                    'profileUrl' => isset($settings['profile_pdf']) ? route('profile.download') : null,
                ];
            },
            'navServices' => fn () => Service::query()->published()->get(['slug', 'title_en', 'title_ar', 'group'])
                ->map(fn (Service $service) => [
                    'slug' => $service->slug,
                    'title' => $service->localized('title'),
                    'group' => $service->group->value,
                ]),
            'meta' => fn () => Seo::make(),
        ];
    }
}
