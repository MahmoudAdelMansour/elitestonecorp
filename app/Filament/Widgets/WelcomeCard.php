<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Widgets\Widget;

class WelcomeCard extends Widget
{
    protected string $view = 'filament.widgets.welcome-card';

    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'name' => auth()->user()?->name,
            'links' => [
                ['label' => __('admin.dashboard.quick_requests'), 'icon' => 'heroicon-o-inbox-arrow-down', 'url' => InquiryResource::getUrl(), 'color' => 'primary', 'external' => false],
                ['label' => __('admin.dashboard.quick_project'), 'icon' => 'heroicon-o-plus-circle', 'url' => ProjectResource::getUrl('create'), 'color' => 'gray', 'external' => false],
                ['label' => __('admin.dashboard.quick_settings'), 'icon' => 'heroicon-o-cog-6-tooth', 'url' => ManageSettings::getUrl(), 'color' => 'gray', 'external' => false],
                ['label' => __('admin.dashboard.quick_site'), 'icon' => 'heroicon-o-globe-alt', 'url' => url('/'.app()->getLocale()), 'color' => 'gray', 'external' => true],
            ],
        ];
    }
}
