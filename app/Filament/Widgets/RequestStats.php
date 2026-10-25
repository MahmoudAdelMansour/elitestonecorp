<?php

namespace App\Filament\Widgets;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Inquiry;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RequestStats extends StatsOverviewWidget
{
    protected static ?int $sort = -5;

    protected function getStats(): array
    {
        $new = Inquiry::query()->where('status', InquiryStatus::New)->count();

        return [
            Stat::make(__('admin.dashboard.new_requests'), $new)
                ->description(__('admin.dashboard.new_requests_help'))
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($new > 0 ? 'danger' : 'success')
                ->url(InquiryResource::getUrl('index', ['filters' => ['status' => ['value' => InquiryStatus::New->value]]])),
            Stat::make(__('admin.dashboard.month_requests'), Inquiry::query()->where('created_at', '>=', now()->startOfMonth())->count())
                ->descriptionIcon('heroicon-m-calendar')
                ->url(InquiryResource::getUrl()),
            Stat::make(__('admin.dashboard.projects'), Project::query()->where('is_published', true)->count())
                ->descriptionIcon('heroicon-m-photo')
                ->url(ProjectResource::getUrl()),
        ];
    }
}
