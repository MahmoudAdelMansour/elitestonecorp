<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ServiceGroup: string implements HasLabel
{
    case Systems = 'systems';
    case Contracting = 'contracting';

    public function getLabel(): string
    {
        return __("admin.service_group.{$this->value}");
    }
}
