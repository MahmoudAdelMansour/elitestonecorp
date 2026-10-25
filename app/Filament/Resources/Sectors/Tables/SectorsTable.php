<?php

namespace App\Filament\Resources\Sectors\Tables;

use App\Models\Sector;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SectorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->imageWidth(96)->imageHeight(64)->extraImgAttributes(['class' => 'rounded-lg object-cover']),
                TextColumn::make('name')
                    ->label(__('admin.sector.name'))
                    ->state(fn (Sector $record) => $record->localized('name'))
                    ->searchable(['name_en', 'name_ar'])
                    ->weight('bold'),
                TextColumn::make('projects_count')->label(__('admin.sector.projects'))->counts('projects')->badge(),
                ToggleColumn::make('is_published')->label(__('admin.common.published')),
            ])
            ->recordActions([
                EditAction::make()->button(),
                DeleteAction::make()->iconButton(),
            ]);
    }
}
