<?php

namespace App\Filament\Resources\AppointmentResource\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Class LatestAppointments
 *
 * This widget displays the latest appointments for today. It retrieves appointments
 * associated with patients and doctors, providing essential details for quick reference.
 */
class LatestAppointments extends BaseWidget
{
    protected static ?string $heading = 'Citas para hoy';

    /**
     * Define the table for displaying the latest appointments.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::with('patient')
                    ->where('date', '>', now()->format('Y-m-d')) // Filter for appointments today
                    ->where('date', '<', now()->addDay()->format('Y-m-d')) // Limit to today's date
                    ->limit(5) // Retrieve a maximum of 5 appointments
            )
            ->columns([
                TextColumn::make('patient')
                    ->getStateUsing(fn (Appointment $appointment) => "{$appointment->patient->first_name} {$appointment->patient->last_name}") // Display patient's full name
                    ->label('Nombre del paciente') // Column label
                    ->searchable(), // Enable search for this column
                TextColumn::make('date')
                    ->label('Cita'), // Column label for appointment date
                TextColumn::make('patient.cellphone')
                    ->label('Teléfono'), // Column label for patient's phone number
                TextColumn::make('user.name')
                    ->label('Doctor'), // Column label for doctor's name
            ]);
    }
}
