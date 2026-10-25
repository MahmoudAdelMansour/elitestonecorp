<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PartnerType: string implements HasColor, HasLabel
{
    case Client = 'client';
    case Partner = 'partner';

    public function getLabel(): string
    {
        return __("admin.partner_type.{$this->value}");
    }

    public function getColor(): string
    {
        return $this === self::Client ? 'primary' : 'gray';
    }
}
