<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestInquiries extends TableWidget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('admin.dashboard.latest'))
            ->query(Inquiry::query()->with('service')->latest()->limit(6))
            ->paginated(false)
            ->emptyStateHeading(__('admin.inquiry.empty'))
            ->emptyStateDescription(__('admin.inquiry.empty_help'))
            ->recordUrl(fn (Inquiry $record) => InquiryResource::getUrl('view', ['record' => $record]))
            ->columns([
                TextColumn::make('status')->label(__('admin.inquiry.status'))->badge(),
                TextColumn::make('name')->label(__('admin.inquiry.name'))->description(fn (Inquiry $record) => $record->company)->weight('bold'),
                TextColumn::make('phone')->label(__('admin.inquiry.phone'))->extraAttributes(['dir' => 'ltr']),
                TextColumn::make('service')->label(__('admin.inquiry.service'))->state(fn (Inquiry $record) => $record->service?->localized('title') ?? '—'),
                TextColumn::make('created_at')->label(__('admin.common.created_at'))->since(),
            ])
            ->recordActions([
                ViewAction::make()->url(fn (Inquiry $record) => InquiryResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
