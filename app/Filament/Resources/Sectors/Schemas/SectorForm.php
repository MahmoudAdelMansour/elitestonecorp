<?php

namespace App\Filament\Resources\Sectors\Schemas;

use App\Filament\Support\Bilingual;
use App\Filament\Support\Fields;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SectorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('admin.sector.label'))
                    ->description(__('admin.common.translations_help'))
                    ->schema([
                        Bilingual::text('name', __('admin.sector.name'), required: true),
                        Bilingual::textarea('description', __('admin.sector.description'), rows: 3, max: 600),
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            Fields::icon()->default('building'),
                            Fields::published(),
                        ]),
                        Fields::image('image', 'sectors'),
                    ]),
                Section::make(__('admin.common.advanced'))
                    ->collapsed()
                    ->columns(2)
                    ->schema([Fields::slug(), Fields::sortOrder()]),
            ]);
    }
}
