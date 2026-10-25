<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum InquiryStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';
    case Contacted = 'contacted';
    case Quoted = 'quoted';
    case Won = 'won';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return __("admin.inquiry_status.{$this->value}");
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'danger',
            self::Contacted => 'warning',
            self::Quoted => 'info',
            self::Won => 'success',
            self::Closed => 'gray',
        };
    }

    public function getIcon(): Heroicon
    {
        return match ($this) {
            self::New => Heroicon::Sparkles,
            self::Contacted => Heroicon::Phone,
            self::Quoted => Heroicon::DocumentText,
            self::Won => Heroicon::CheckBadge,
            self::Closed => Heroicon::ArchiveBox,
        };
    }
}
