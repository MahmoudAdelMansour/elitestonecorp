<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columns(['default' => 1, 'md' => 2])
                    ->schema([
                        TextInput::make('name')->label(__('admin.user.name'))->required()->maxLength(120),
                        TextInput::make('email')->label(__('admin.user.email'))->email()->required()->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label(__('admin.user.password'))
                            ->password()
                            ->revealable()
                            ->rule(Password::min(8))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->helperText(fn (string $operation) => $operation === 'edit' ? __('admin.user.password_help') : null),
                        ToggleButtons::make('locale')
                            ->label(__('admin.user.locale'))
                            ->options(['ar' => 'العربية', 'en' => 'English'])
                            ->default('ar')
                            ->inline()
                            ->required(),
                    ]),
            ]);
    }
}
