<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Enums\ServiceGroup;
use App\Filament\Support\Bilingual;
use App\Filament\Support\Fields;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('admin.service.label'))
                    ->description(__('admin.common.translations_help'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Bilingual::text('title', __('admin.service.title'), required: true),
                        Grid::make(['default' => 1, 'md' => 3])->schema([
                            Select::make('group')
                                ->label(__('admin.service.group'))
                                ->options(ServiceGroup::class)
                                ->default(ServiceGroup::Systems)
                                ->required(),
                            Fields::icon()->default('layers'),
                            Fields::published(),
                        ]),
                        Fields::image('image', 'services'),
                    ]),
                Section::make(__('admin.project.texts'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Bilingual::textarea('excerpt', __('admin.service.excerpt'), required: true, helper: __('admin.service.excerpt_help'), rows: 2, max: 500),
                        Bilingual::textarea('body', __('admin.service.body'), helper: __('admin.service.body_help'), rows: 8),
                        Bilingual::textarea('features', __('admin.service.features'), helper: __('admin.service.features_help'), rows: 6),
                    ]),
                Section::make(__('admin.common.advanced'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed()
                    ->columns(2)
                    ->schema([Fields::slug(), Fields::sortOrder()]),
            ]);
    }
}
