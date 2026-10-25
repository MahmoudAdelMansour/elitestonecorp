<?php

namespace Tests\Feature;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    /**
     * @return array<string, array{string}>
     */
    public static function screens(): array
    {
        return [
            'dashboard' => ['/admin'],
            'inquiries' => ['/admin/inquiries'],
            'projects' => ['/admin/projects'],
            'project create' => ['/admin/projects/create'],
            'services' => ['/admin/services'],
            'sectors' => ['/admin/sectors'],
            'partners' => ['/admin/partners'],
            'settings' => ['/admin/manage-settings'],
            'users' => ['/admin/users'],
        ];
    }

    #[DataProvider('screens')]
    public function test_admin_screens_load_in_arabic_and_english(string $url): void
    {
        $user = User::query()->first();

        foreach (['ar', 'en'] as $locale) {
            $user->update(['locale' => $locale]);
            $this->actingAs($user)->get($url)->assertOk()->assertSee("lang=\"{$locale}\"", false);
        }
    }

    public function test_edit_and_view_screens_load(): void
    {
        $inquiry = Inquiry::query()->create([
            'name' => 'Test', 'email' => 'a@b.test', 'phone' => '0100', 'message' => 'Hello there team',
        ]);

        $this->actingAs(User::query()->first())
            ->get(ProjectResource::getUrl('edit', ['record' => Project::query()->first()]))->assertOk();
        $this->actingAs(User::query()->first())
            ->get(InquiryResource::getUrl('view', ['record' => $inquiry]))->assertOk()->assertSee($inquiry->reference());
    }

    public function test_admins_can_switch_the_panel_language(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)->from('/admin')->get('/admin-language/en')->assertRedirect('/admin');
        $this->assertSame('en', $user->fresh()->locale);
    }
}
