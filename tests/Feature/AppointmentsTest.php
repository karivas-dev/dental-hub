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

/**
 * Test if the Appointment index page renders successfully
 * and the table displays only the records of the user's branch.
 */
it('can render index', function () {
    get(AppointmentResource::getUrl('index'))->assertSuccessful();

    livewire(AppointmentResource\Pages\ListAppointments::class)
        ->assertCanSeeTableRecords(Appointment::where('branch_id', Auth::user()->branch_id)->limit(5)->get())
        ->assertCanNotSeeTableRecords(Appointment::where('branch_id', '!=', Auth::user()->branch_id)->limit(5)->get());
});

/**
 * Test if the Appointment create page renders successfully.
 */
it('can render create', function () {
    get(AppointmentResource::getUrl('create'))->assertSuccessful();
});

/**
 * Test the creation of a new Appointment and assert that it is stored in the database.
 */
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

/**
 * Test the validation of input when creating an Appointment.
 * Ensure invalid inputs trigger appropriate validation errors.
 */
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

/**
 * Test if the Appointment edit page renders successfully.
 */
it('can render edit', function () {
    get(AppointmentResource::getUrl('edit', [Appointment::factory()->create()]))->assertSuccessful();
});

/**
 * Test if an Appointment's data is correctly retrieved when editing.
 */
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

/**
 * Test if an Appointment can be updated and the changes
 * are reflected in the database.
 */
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

/**
 * Test if an Appointment can be soft deleted.
 */
it('can delete', function () {
    $appointment = Appointment::factory()->create();

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->callAction(DeleteAction::class);

    assertSoftDeleted($appointment);
});

/**
 * Test if multiple Appointments can be soft deleted in bulk.
 */
it('can bulk delete', function () {
    $appointments = Appointment::factory(2)->create();

    livewire(AppointmentResource\Pages\ListAppointments::class)
        ->callTableBulkAction(DeleteBulkAction::class, $appointments);

    $appointments->each(fn($appointment) => assertSoftDeleted($appointment));
});

/**
 * Test if a soft deleted Appointment can be restored.
 */
it('can restore', function () {
    $appointment = Appointment::factory()->trashed()->create();

    livewire(AppointmentResource\Pages\EditAppointment::class, ['record' => $appointment->getRouteKey()])
        ->callAction(RestoreAction::class);

    assertNotSoftDeleted($appointment);
});

/**
 * Test if multiple soft deleted Appointments can be restored in bulk.
 * Uncomment the code to activate the test.
 */
// it('can bulk restore', function () {
//     $appointments = Appointment::factory(2)->trashed()->create();
//
//     livewire(AppointmentResource\Pages\ListAppointments::class)
//         ->callTableBulkAction(RestoreBulkAction::class, $appointments);
//
//     $appointments->each(fn($appointment) => assertNotSoftDeleted($appointment));
// });
