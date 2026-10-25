<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;

/**
 * English + Arabic inputs side by side, so admins always see both languages together.
 */
class Bilingual
{
    public static function text(string $name, string $label, bool $required = false, ?string $helper = null, int $max = 255): Grid
    {
        return static::pair($name, $label, $helper, fn (string $field) => TextInput::make($field)->maxLength($max), $required);
    }

    public static function textarea(string $name, string $label, bool $required = false, ?string $helper = null, int $rows = 4, int $max = 5000): Grid
    {
        return static::pair($name, $label, $helper, fn (string $field) => Textarea::make($field)->rows($rows)->autosize()->maxLength($max), $required);
    }

    private static function pair(string $name, string $label, ?string $helper, callable $make, bool $required): Grid
    {
        return Grid::make(['default' => 1, 'md' => 2])
            ->columnSpanFull()
            ->schema([
                $make("{$name}_en")
                    ->label($label.' — '.__('admin.english'))
                    ->required($required)
                    ->helperText($helper)
                    ->extraInputAttributes(['dir' => 'ltr', 'lang' => 'en']),
                $make("{$name}_ar")
                    ->label($label.' — '.__('admin.arabic'))
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar']),
            ]);
    }
}
