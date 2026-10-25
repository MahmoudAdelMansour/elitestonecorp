<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInquiry extends ViewRecord
{
    protected static string $resource = InquiryResource::class;

    public function getTitle(): string
    {
        return $this->record->name.' · '.$this->record->reference();
    }

    protected function getHeaderActions(): array
    {
        return [
            InquiryResource::statusAction()->button()->color('primary'),
            ...InquiryResource::contactActions(),
            DeleteAction::make(),
        ];
    }
}
