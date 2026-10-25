<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Models\Inquiry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Grid::make(1)->columnSpan(['lg' => 2])->schema([
                    Section::make(__('admin.inquiry.contact'))
                        ->icon('heroicon-o-user')
                        ->columns(2)
                        ->schema([
                            TextEntry::make('name')->label(__('admin.inquiry.name'))->weight('bold'),
                            TextEntry::make('company')->label(__('admin.inquiry.company'))->placeholder('—'),
                            TextEntry::make('phone')->label(__('admin.inquiry.phone'))->copyable()->extraAttributes(['dir' => 'ltr']),
                            TextEntry::make('email')->label(__('admin.inquiry.email'))->copyable(),
                            TextEntry::make('job_title')->label(__('admin.inquiry.job_title'))->placeholder('—'),
                        ]),
                    Section::make(__('admin.inquiry.project'))
                        ->icon('heroicon-o-clipboard-document-list')
                        ->columns(2)
                        ->schema([
                            TextEntry::make('service')->label(__('admin.inquiry.service'))
                                ->state(fn (Inquiry $record) => $record->service?->localized('title'))->placeholder('—'),
                            TextEntry::make('sector')->label(__('admin.inquiry.sector'))
                                ->state(fn (Inquiry $record) => $record->sector?->localized('name'))->placeholder('—'),
                            TextEntry::make('location')->label(__('admin.inquiry.location'))->placeholder('—'),
                            TextEntry::make('area')->label(__('admin.inquiry.area'))->placeholder('—'),
                            TextEntry::make('timeline')->label(__('admin.inquiry.timeline'))
                                ->formatStateUsing(fn (?string $state) => $state ? __("admin.timelines.{$state}") : null)->placeholder('—'),
                            TextEntry::make('message')->label(__('admin.inquiry.message'))->columnSpanFull()->prose(),
                        ]),
                ]),
                Section::make(__('admin.inquiry.follow_up'))
                    ->icon('heroicon-o-flag')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('status')->label(__('admin.inquiry.status'))->badge()->size('lg'),
                        TextEntry::make('reference')->label(__('admin.inquiry.reference'))
                            ->state(fn (Inquiry $record) => $record->reference())->copyable(),
                        TextEntry::make('created_at')->label(__('admin.common.created_at'))->dateTime('d M Y, h:i A'),
                        TextEntry::make('locale')->label(__('admin.inquiry.locale'))
                            ->formatStateUsing(fn (string $state) => $state === 'ar' ? __('admin.arabic') : __('admin.english')),
                        TextEntry::make('internal_notes')->label(__('admin.inquiry.notes'))->placeholder('—'),
                    ]),
            ]);
    }
}
