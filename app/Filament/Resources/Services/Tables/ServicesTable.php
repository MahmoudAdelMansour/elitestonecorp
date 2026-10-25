<?php

namespace App\Filament\Resources\Services\Tables;

use App\Models\Service;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->imageWidth(96)->imageHeight(64)->extraImgAttributes(['class' => 'rounded-lg object-cover']),
                TextColumn::make('title')
                    ->label(__('admin.service.title'))
                    ->state(fn (Service $record) => $record->localized('title'))
                    ->description(fn (Service $record) => $record->group->getLabel())
                    ->searchable(['title_en', 'title_ar'])
                    ->weight('bold'),
                TextColumn::make('projects_count')->label(__('admin.sector.projects'))->counts('projects')->badge(),
                ToggleColumn::make('is_published')->label(__('admin.common.published')),
            ])
            ->recordActions([
                EditAction::make()->button(),
                ActionGroup::make([
                    Action::make('website')
                        ->label(__('admin.view_website'))
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (Service $record) => url(app()->getLocale().'/services/'.$record->slug))
                        ->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
