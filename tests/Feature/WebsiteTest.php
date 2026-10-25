<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);
    }

    public function test_root_redirects_to_the_visitors_preferred_language(): void
    {
        $this->get('/', ['Accept-Language' => 'ar-EG,ar;q=0.9'])->assertRedirect('/ar');
        $this->get('/', ['Accept-Language' => 'en-US'])->assertRedirect('/en');
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function pages(): array
    {
        return [
            'home' => ['', 'Home'],
            'about' => ['/about', 'About'],
            'services' => ['/services', 'Services/Index'],
            'service' => ['/services/hygienic-flooring', 'Services/Show'],
            'projects' => ['/projects', 'Projects/Index'],
            'project' => ['/projects/esca-cueva-restaurant-flooring', 'Projects/Show'],
            'contact' => ['/contact', 'Contact'],
        ];
    }

    #[DataProvider('pages')]
    public function test_every_page_renders_in_both_languages(string $path, string $component): void
    {
        foreach (['en' => 'ltr', 'ar' => 'rtl'] as $locale => $dir) {
            $this->get("/{$locale}{$path}")
                ->assertOk()
                ->assertSee("lang=\"{$locale}\" dir=\"{$dir}\"", false)
                ->assertInertia(fn (Assert $page) => $page
                    ->component($component)
                    ->where('locale', $locale)
                    ->where('dir', $dir)
                    ->has('meta.title'));
        }
    }

    public function test_arabic_content_survives_json_encoding(): void
    {
        $this->get('/ar/services/hygienic-flooring')
            ->assertInertia(fn (Assert $page) => $page
                ->where('service.title', 'الأرضيات الصحية')
                ->where('service.features.0', 'أنظمة بولي يوريثان أسمنتي وإيبوكسي متصلة'));
    }

    public function test_projects_can_be_filtered_by_sector_and_service(): void
    {
        $this->get('/en/projects?sector=industrial')
            ->assertInertia(fn (Assert $page) => $page->has('projects', 4));

        $this->get('/en/projects?service=concrete-repair')
            ->assertInertia(fn (Assert $page) => $page
                ->has('projects', Project::query()->whereRelation('services', 'slug', 'concrete-repair')->count()));
    }

    public function test_unpublished_content_is_hidden(): void
    {
        Service::query()->where('slug', 'injection')->update(['is_published' => false]);

        $this->get('/en/services/injection')->assertNotFound();
    }

    public function test_unknown_pages_show_the_branded_error_page(): void
    {
        $this->get('/ar/does-not-exist')
            ->assertNotFound()
            ->assertInertia(fn (Assert $page) => $page->component('Error')->where('status', 404)->where('locale', 'ar'));
    }

    public function test_sitemap_lists_both_languages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(url('/ar/services/industrial-flooring'))
            ->assertSee('hreflang="en"', false);
    }

    public function test_company_profile_can_be_downloaded(): void
    {
        $this->get('/company-profile.pdf')->assertOk()->assertDownload('Elite-Stone-Company-Profile.pdf');
    }
}
