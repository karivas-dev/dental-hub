<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAppointments extends BaseWidget
{
    protected static ?string $heading = 'Citas para hoy';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::with('patient')
                    ->where('date', '>', now()->format('Y-m-d'))
                    ->where('date', '<', now()->addDay()->format('Y-m-d'))
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('patient')
                    ->getStateUsing(fn(Appointment $appointment) => "{$appointment->patient->first_name} {$appointment->patient->last_name}")
                    ->label('Nombre del paciente')
                    ->searchable(),
                TextColumn::make('date')

                    ->label('Cita'),
                TextColumn::make('patient.cellphone')
                    ->label('Teléfono'),
                TextColumn::make('user.name')
                    ->label('Doctor'),
            ]);
    }
}
