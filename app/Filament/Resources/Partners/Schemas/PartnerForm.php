<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Enums\PartnerType;
use App\Filament\Support\Fields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(['default' => 1, 'md' => 2])
                    ->schema([
                        TextInput::make('name')->label(__('admin.partner.name'))->required()->maxLength(160),
                        ToggleButtons::make('type')
                            ->label(__('admin.partner.type'))
                            ->options(PartnerType::class)
                            ->default(PartnerType::Client)
                            ->inline()
                            ->required(),
                        FileUpload::make('logo')
                            ->label(__('admin.partner.logo'))
                            ->helperText(__('admin.partner.logo_help'))
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('partners')
                            ->visibility('public')
                            ->maxSize(5 * 1024)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('website')->label(__('admin.partner.website'))->url()->maxLength(255),
                        Fields::published(),
                        Fields::sortOrder(),
                    ]),
            ]);
    }
}
