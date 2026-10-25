<?php

namespace Tests\Feature;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewInquiry;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);
        Notification::fake();
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'name' => 'Omar Hassan',
            'company' => 'Delta Foods',
            'email' => 'omar@deltafoods.test',
            'phone' => '01001234567',
            'service_id' => Service::query()->where('slug', 'hygienic-flooring')->value('id'),
            'area' => '1200',
            'timeline' => '1_month',
            'message' => 'We need a hygienic floor for our new production hall.',
            ...$overrides,
        ];
    }

    public function test_a_visitor_can_request_a_quote(): void
    {
        $this->from('/ar/contact')
            ->post('/ar/contact', $this->payload())
            ->assertRedirect('/ar/contact')
            ->assertSessionHasNoErrors();

        $inquiry = Inquiry::query()->sole();
        $this->assertSame(InquiryStatus::New, $inquiry->status);
        $this->assertSame('ar', $inquiry->locale);
        $this->assertMatchesRegularExpression('/^ES-\d{2}-0001$/', $inquiry->reference());

        Notification::assertSentTo(new AnonymousNotifiable, NewInquiry::class, fn ($n, $channels, $notifiable) => $notifiable->routes['mail'] === 'info@elitestone-corp.com');
        Notification::assertSentTo(User::query()->first(), NewInquiry::class);
    }

    public function test_required_fields_are_validated_in_the_visitors_language(): void
    {
        $this->from('/en/contact')
            ->post('/en/contact', $this->payload(['name' => '', 'email' => 'not-an-email', 'phone' => 'abc', 'message' => '']))
            ->assertSessionHasErrors(['name', 'email', 'phone', 'message']);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_bots_filling_the_honeypot_are_rejected(): void
    {
        $this->post('/en/contact', $this->payload(['website' => 'http://spam.test']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('inquiries', 0);
    }
}
