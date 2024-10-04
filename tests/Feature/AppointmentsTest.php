<?php

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render index', function () {
    get(AppointmentResource::getUrl('index'))->assertSuccessful();
});

it('can render create', function () {
    get(AppointmentResource::getUrl('create'))->assertSuccessful();
});

it('can create appointment', function () {
    $newAppointment = Appointment::factory()->make([
        'date' => now()->addDay(),
    ]);

    livewire(AppointmentResource\Pages\CreateAppointment::class)
        ->fillForm($newAppointment->toArray())
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Appointment::class, [
        'date' => $newAppointment->date,
        'status' => $newAppointment->status,
        'amount' => $newAppointment->amount,
        'user_id' => $newAppointment->user_id,
        'patient_id' => $newAppointment->patient_id,
    ]);
});

it('can validate input', function () {
    livewire(AppointmentResource\Pages\CreateAppointment::class)
        ->fillForm([
            'date' => now()->subDay(),
            'amount' => -1,
            'user_id' => 0,
            'patient_id' => 0,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'date' => 'after',
            'status' => 'required',
            'amount' => 'min',
            'user_id' => 'exists',
            'patient_id' => 'exists',
        ]);
});

// it('can render view', function () {
//     get(AppointmentResource::getUrl('view', [Appointment::factory()->create()]))->assertSuccessful();
// });

it('can render edit', function () {
    get(AppointmentResource::getUrl('edit', [Appointment::factory()->create()]))->assertSuccessful();
});

it('can retrieve data', function () {
    $appointment = Appointment::factory()->create();

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->assertFormSet([
            'date' => $appointment->date,
            'details' => $appointment->details,
            'status' => $appointment->status->value,
            'amount' => $appointment->amount,
            'user_id' => $appointment->user_id,
            'patient_id' => $appointment->patient_id,
        ]);
});

it('can update appointment', function () {
    $appointment = Appointment::factory()->create();
    $newAppointment = Appointment::factory()->make([
        'date' => now()->addDay(),
    ]);

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->fillForm($newAppointment->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Appointment::class, [
        'id' => $appointment->id,
        'date' => $newAppointment->date,
        'status' => $newAppointment->status,
        'amount' => $newAppointment->amount,
        'user_id' => $newAppointment->user_id,
        'patient_id' => $newAppointment->patient_id,
    ]);
});

it('can delete', function () {
    $appointment = Appointment::factory()->create();

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->callAction(DeleteAction::class);

    assertSoftDeleted($appointment);
});

it('can bulk delete', function () {
    $appointments = Appointment::factory(2)->create();

    livewire(AppointmentResource\Pages\ListAppointments::class)
        ->callTableBulkAction(DeleteBulkAction::class, $appointments);

    $appointments->each(fn($appointment) => assertSoftDeleted($appointment));
});

it('can restore', function () {
    $appointment = Appointment::factory()->trashed()->create();

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->callAction(RestoreAction::class);

    assertNotSoftDeleted($appointment);
});

//it('can bulk restore', function () {
//    $appointments = Appointment::factory(2)->trashed()->create();
//
//    livewire(AppointmentResource\Pages\ListAppointments::class)
//        ->callTableBulkAction(RestoreBulkAction::class, $appointments);
//
//    $appointments->each(fn($appointment) => assertNotSoftDeleted($appointment));
//});
