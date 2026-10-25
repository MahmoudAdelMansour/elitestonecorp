<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use App\Models\Sector;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('public')
                    ->imageWidth(96)
                    ->imageHeight(64)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover']),
                TextColumn::make('title')
                    ->label(__('admin.project.title'))
                    ->state(fn (Project $record) => $record->localized('title'))
                    ->description(fn (Project $record) => $record->sector?->localized('name'))
                    ->searchable(['title_en', 'title_ar'])
                    ->weight('bold')
                    ->wrap(),
                TextColumn::make('year')
                    ->label(__('admin.project.year'))
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->label(__('admin.project.featured')),
                ToggleColumn::make('is_published')
                    ->label(__('admin.common.published')),
            ])
            ->filters([
                SelectFilter::make('sector_id')
                    ->label(__('admin.project.sector'))
                    ->options(fn () => Sector::query()->orderBy('sort_order')->get()->mapWithKeys(fn (Sector $s) => [$s->id => $s->localized('name')])),
                TernaryFilter::make('is_featured')->label(__('admin.project.featured')),
                TernaryFilter::make('is_published')->label(__('admin.common.published')),
            ])
            ->recordActions([
                EditAction::make()->button(),
                ActionGroup::make([
                    Action::make('website')
                        ->label(__('admin.view_website'))
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (Project $record) => url(app()->getLocale().'/projects/'.$record->slug))
                        ->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
