<?php

namespace App\Http\Controllers;

use App\Enums\PartnerType;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SectorResource;
use App\Http\Resources\ServiceResource;
use App\Models\Partner;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PageController extends Controller
{
    public function home(): Response
    {
        $partners = Partner::query()->published()->get()->groupBy(fn (Partner $p) => $p->type->value);

        return Inertia::render('Home', [
            'heroImage' => Seo::storageUrl(Setting::get('hero_image')),
            'aboutImage' => Seo::storageUrl(Setting::get('about_image')),
            'services' => ServiceResource::collection(Service::query()->published()->get())->resolve(),
            'sectors' => SectorResource::collection(
                Sector::query()->published()->withCount(['projects' => fn ($q) => $q->where('is_published', true)])->get()
            )->resolve(),
            'projects' => ProjectResource::collection(
                Project::query()->published()->where('is_featured', true)->with(['sector', 'client', 'services'])->limit(6)->get()
            )->resolve(),
            'clients' => PartnerResource::collection($partners->get(PartnerType::Client->value, collect()))->resolve(),
            'partners' => PartnerResource::collection($partners->get(PartnerType::Partner->value, collect()))->resolve(),
            'stats' => $this->stats(),
            'meta' => Seo::make(schema: [$this->organizationSchema(), $this->faqSchema()]),
        ]);
    }

    public function about(): Response
    {
        $partners = Partner::query()->published()->get()->groupBy(fn (Partner $p) => $p->type->value);

        return Inertia::render('About', [
            'image' => Seo::storageUrl(Setting::get('about_image')),
            'gallery' => Project::query()->published()->where('is_featured', true)->limit(3)->pluck('cover_image')
                ->map(fn (?string $path) => Seo::storageUrl($path))->filter()->values(),
            'clients' => PartnerResource::collection($partners->get(PartnerType::Client->value, collect()))->resolve(),
            'partners' => PartnerResource::collection($partners->get(PartnerType::Partner->value, collect()))->resolve(),
            'stats' => $this->stats(),
            'meta' => Seo::make(__('site.about.page_title'), __('site.about.body'), Setting::get('about_image')),
        ]);
    }

    public function profile(): StreamedResponse
    {
        $path = Setting::get('profile_pdf');
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download($path, 'Elite-Stone-Company-Profile.pdf');
    }

    /**
     * @return array<string, int>
     */
    private function stats(): array
    {
        return [
            'years' => max(1, now()->year - (int) Setting::get('founded_year', 2019)),
            'projects' => Project::query()->where('is_published', true)->count(),
            'sectors' => Sector::query()->where('is_published', true)->count(),
            'partners' => Partner::query()->where('is_published', true)->where('type', PartnerType::Partner)->count(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationSchema(): array
    {
        $sameAs = array_values(array_filter([Setting::get('facebook'), Setting::get('instagram'), Setting::get('linkedin')]));

        return [
            '@context' => 'https://schema.org',
            '@type' => 'GeneralContractor',
            'name' => 'Elite Stone',
            'alternateName' => 'إيليت ستون',
            'slogan' => 'From Concept to Creation',
            'url' => url('/'),
            'logo' => asset('images/brand/logo-badge-navy.jpg'),
            'image' => Seo::storageUrl(Setting::get('hero_image')),
            'telephone' => Setting::get('phone'),
            'email' => Setting::get('email'),
            'foundingDate' => (string) Setting::get('founded_year', 2019),
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Cairo', 'addressCountry' => 'EG'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Egypt'],
            'sameAs' => $sameAs,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function faqSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect(__('site.faq.items'))->map(fn (array $item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
            ])->all(),
        ];
    }
}
