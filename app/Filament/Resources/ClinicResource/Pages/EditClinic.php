<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Resources\Pages\EditRecord;

class EditClinic extends EditRecord
{
    protected static string $resource = ClinicResource::class;

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

//    protected function getHeaderActions(): array
//    {
//        return [
//            Actions\ViewAction::make(),
//            Actions\DeleteAction::make(),
//        ];
//    }
}
