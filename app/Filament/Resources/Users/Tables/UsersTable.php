<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('admin.user.name'))->weight('bold')->searchable(),
                TextColumn::make('email')->label(__('admin.user.email'))->searchable(),
                TextColumn::make('locale')->label(__('admin.user.locale'))->formatStateUsing(fn (string $state) => $state === 'ar' ? 'العربية' : 'English'),
            ])
            ->recordActions([
                EditAction::make()->button(),
                DeleteAction::make()->iconButton()->hidden(fn (User $record) => $record->is(auth()->user())),
            ]);
    }
}
