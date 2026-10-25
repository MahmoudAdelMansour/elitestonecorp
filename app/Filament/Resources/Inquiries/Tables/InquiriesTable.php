<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('service'))
            ->emptyStateHeading(__('admin.inquiry.empty'))
            ->emptyStateDescription(__('admin.inquiry.empty_help'))
            ->emptyStateIcon('heroicon-o-inbox')
            ->columns([
                TextColumn::make('status')
                    ->label(__('admin.inquiry.status'))
                    ->badge(),
                TextColumn::make('name')
                    ->label(__('admin.inquiry.name'))
                    ->description(fn (Inquiry $record) => $record->company)
                    ->searchable(['name', 'company', 'email', 'phone'])
                    ->weight(fn (Inquiry $record) => $record->status === InquiryStatus::New ? 'bold' : null),
                TextColumn::make('phone')
                    ->label(__('admin.inquiry.phone'))
                    ->copyable()
                    ->extraAttributes(['dir' => 'ltr']),
                TextColumn::make('service')
                    ->label(__('admin.inquiry.service'))
                    ->state(fn (Inquiry $record) => $record->service?->localized('title') ?? '—')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('admin.common.created_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label(__('admin.inquiry.reference'))
                    ->state(fn (Inquiry $record) => $record->reference())
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('admin.inquiry.status'))->options(InquiryStatus::class),
            ])
            ->recordActions([
                ViewAction::make()->button(),
                ActionGroup::make([
                    ...InquiryResource::contactActions(),
                    InquiryResource::statusAction(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
