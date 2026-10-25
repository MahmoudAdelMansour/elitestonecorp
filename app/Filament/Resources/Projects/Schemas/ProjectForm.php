<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\PartnerType;
use App\Filament\Support\Bilingual;
use App\Filament\Support\Fields;
use App\Models\Sector;
use App\Models\Service;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('admin.project.details'))
                    ->description(__('admin.common.translations_help'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Bilingual::text('title', __('admin.project.title'), required: true),
                        Grid::make(['default' => 1, 'md' => 2, 'xl' => 4])->schema([
                            Select::make('sector_id')
                                ->label(__('admin.project.sector'))
                                ->relationship('sector', 'name_'.app()->getLocale())
                                ->getOptionLabelFromRecordUsing(fn (Sector $record) => $record->localized('name'))
                                ->preload()
                                ->required(),
                            TextInput::make('year')
                                ->label(__('admin.project.year'))
                                ->numeric()
                                ->minValue(2000)
                                ->maxValue(2100)
                                ->default(now()->year)
                                ->required(),
                            Select::make('client_id')
                                ->label(__('admin.project.client'))
                                ->helperText(__('admin.project.client_help'))
                                ->relationship('client', 'name', fn ($query) => $query->where('type', PartnerType::Client))
                                ->searchable()
                                ->preload(),
                            TextInput::make('area')
                                ->label(__('admin.project.area'))
                                ->helperText(__('admin.project.area_help'))
                                ->maxLength(40),
                        ]),
                        Bilingual::text('location', __('admin.project.location')),
                        CheckboxList::make('services')
                            ->label(__('admin.project.services'))
                            ->relationship('services', 'title_'.app()->getLocale())
                            ->getOptionLabelFromRecordUsing(fn (Service $record) => $record->localized('title'))
                            ->columns(['default' => 1, 'md' => 3])
                            ->gridDirection('row'),
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            Toggle::make('is_featured')
                                ->label(__('admin.project.featured'))
                                ->helperText(__('admin.project.featured_help')),
                            Fields::published(),
                        ]),
                    ]),

                Section::make(__('admin.project.photos'))
                    ->icon('heroicon-o-camera')
                    ->schema([
                        Fields::image('cover_image', 'projects', __('admin.project.cover'))->required(),
                        FileUpload::make('gallery')
                            ->label(__('admin.project.gallery'))
                            ->helperText(__('admin.project.gallery_help'))
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->disk('public')
                            ->directory('projects/gallery')
                            ->visibility('public')
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1920')
                            ->maxSize(15 * 1024)
                            ->maxFiles(30),
                    ]),

                Section::make(__('admin.project.texts'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Bilingual::textarea('summary', __('admin.project.summary'), helper: __('admin.project.summary_help'), rows: 2, max: 500),
                        Bilingual::textarea('challenge', __('admin.project.challenge')),
                        Bilingual::textarea('solution', __('admin.project.solution')),
                    ]),

                Section::make(__('admin.common.advanced'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed()
                    ->columns(2)
                    ->schema([
                        Fields::slug(),
                        Fields::sortOrder(),
                    ]),
            ]);
    }
}
