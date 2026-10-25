<?php

namespace App\Filament\Resources\Inquiries;

use App\Enums\InquiryStatus;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Inquiries\Pages\ViewInquiry;
use App\Filament\Resources\Inquiries\Schemas\InquiryInfolist;
use App\Filament\Resources\Inquiries\Tables\InquiriesTable;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?int $navigationSort = 0;

    public static function getModelLabel(): string
    {
        return __('admin.inquiry.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.inquiry.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.requests');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Inquiry::query()->where('status', InquiryStatus::New)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return InquiryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InquiriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'view' => ViewInquiry::route('/{record}'),
        ];
    }

    /**
     * Quick-contact and follow-up actions shared by the list and the detail page.
     *
     * @return array<int, Action>
     */
    public static function contactActions(): array
    {
        return [
            Action::make('call')
                ->label(__('admin.inquiry.call'))
                ->icon('heroicon-o-phone')
                ->color('gray')
                ->url(fn (Inquiry $record) => 'tel:'.preg_replace('/[^0-9+]/', '', $record->phone)),
            Action::make('whatsapp')
                ->label(__('admin.inquiry.whatsapp'))
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn (Inquiry $record) => 'https://wa.me/'.self::whatsappNumber($record->phone))
                ->openUrlInNewTab(),
            Action::make('email')
                ->label(__('admin.inquiry.reply'))
                ->icon('heroicon-o-envelope')
                ->color('gray')
                ->url(fn (Inquiry $record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Elite Stone – '.$record->reference())),
        ];
    }

    public static function statusAction(): Action
    {
        return Action::make('changeStatus')
            ->label(__('admin.inquiry.change_status'))
            ->icon('heroicon-o-arrow-path')
            ->fillForm(fn (Inquiry $record) => ['status' => $record->status, 'internal_notes' => $record->internal_notes])
            ->schema([
                ToggleButtons::make('status')
                    ->label(__('admin.inquiry.status'))
                    ->options(InquiryStatus::class)
                    ->inline()
                    ->required(),
                Textarea::make('internal_notes')
                    ->label(__('admin.inquiry.notes'))
                    ->helperText(__('admin.inquiry.notes_help'))
                    ->rows(4),
            ])
            ->action(function (Inquiry $record, array $data) {
                $record->update($data);
                Notification::make()->title(__('admin.inquiry.status_updated'))->success()->send();
            });
    }

    /** Egyptian numbers are often typed locally ("010…"); WhatsApp needs the country code. */
    public static function whatsappNumber(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        return str_starts_with($digits, '0') && strlen($digits) === 11 ? '20'.substr($digits, 1) : $digits;
    }
}
