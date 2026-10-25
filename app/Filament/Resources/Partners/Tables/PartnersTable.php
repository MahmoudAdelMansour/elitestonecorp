<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Enums\PartnerType;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('logo')->label('')->disk('public')->imageHeight(48)->extraImgAttributes(['class' => 'object-contain bg-white rounded p-1']),
                TextColumn::make('name')->label(__('admin.partner.name'))->searchable()->weight('bold'),
                TextColumn::make('type')->label(__('admin.partner.type'))->badge(),
                ToggleColumn::make('is_published')->label(__('admin.common.published')),
            ])
            ->filters([
                SelectFilter::make('type')->label(__('admin.partner.type'))->options(PartnerType::class),
            ])
            ->recordActions([
                EditAction::make()->button(),
                DeleteAction::make()->iconButton(),
            ]);
    }
}
