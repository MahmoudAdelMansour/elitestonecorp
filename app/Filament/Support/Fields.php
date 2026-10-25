<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class Fields
{
    public static function image(string $name, string $directory, ?string $label = null): FileUpload
    {
        return FileUpload::make($name)
            ->label($label ?? __('admin.common.image'))
            ->helperText(__('admin.common.image_help'))
            ->image()
            ->imageEditor()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('1920')
            ->imageResizeTargetHeight('1920')
            ->maxSize(15 * 1024);
    }

    public static function published(): Toggle
    {
        return Toggle::make('is_published')
            ->label(__('admin.common.published'))
            ->helperText(__('admin.common.published_help'))
            ->default(true);
    }

    public static function sortOrder(): TextInput
    {
        return TextInput::make('sort_order')
            ->label(__('admin.common.sort_order'))
            ->helperText(__('admin.common.sort_order_help'))
            ->numeric()
            ->default(0);
    }

    public static function slug(): TextInput
    {
        return TextInput::make('slug')
            ->label(__('admin.common.slug'))
            ->helperText(__('admin.common.slug_help'))
            ->maxLength(160)
            ->unique(ignoreRecord: true);
    }

    public static function icon(): Select
    {
        return Select::make('icon')
            ->label(__('admin.common.icon'))
            ->options([
                'factory' => 'Factory · مصنع',
                'gem' => 'Gem · جوهرة',
                'shield-check' => 'Shield · درع',
                'hammer' => 'Hammer · مطرقة',
                'droplets' => 'Water · مياه',
                'syringe' => 'Injection · حقن',
                'pen-tool' => 'Design · تصميم',
                'paint-roller' => 'Paint roller · رولة دهان',
                'clipboard-check' => 'Checklist · قائمة مهام',
                'wheat' => 'Food · غذاء',
                'hospital' => 'Hospital · مستشفى',
                'concierge-bell' => 'Hotel · فندق',
                'graduation-cap' => 'Education · تعليم',
                'store' => 'Store · متجر',
                'building' => 'Building · مبنى',
                'layers' => 'Layers · طبقات',
                'hard-hat' => 'Hard hat · خوذة',
                'sparkles' => 'Sparkles · لمعان',
                'lightbulb' => 'Idea · فكرة',
                'heart-handshake' => 'Partnership · شراكة',
            ])
            ->native(false)
            ->required();
    }
}
