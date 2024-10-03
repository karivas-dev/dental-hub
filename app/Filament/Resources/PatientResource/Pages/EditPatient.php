<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;
    protected static ?string $navigationLabel = 'Editar paciente';
    public ?string $heading = 'Detalles del paciente';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
