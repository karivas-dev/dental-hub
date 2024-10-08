<?php

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use Filament\Facades\Filament;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

/**
 * Test rendering the Patient index page.
 * Ensures the index page can be accessed and that the table displays the correct records.
 */
it('can render index', function () {
    get(PatientResource::getUrl('index'))->assertSuccessful();

    livewire(PatientResource\Pages\ListPatients::class)
        ->assertCanSeeTableRecords(Patient::where('clinic_id', Filament::getTenant())->limit(5)->get());
});

/**
 * Test rendering the Patient create page.
 * Asserts that the create page loads successfully.
 */
it('can render create', function () {
    get(PatientResource::getUrl('create'))->assertSuccessful();
});

/**
 * Test creating a new Patient.
 * Ensures that the form can be filled and submitted successfully,
 * and the new patient is stored in the database.
 */
it('can create patient', function () {
    $newPatient = Patient::factory()->make();

    livewire(PatientResource\Pages\CreatePatient::class)
        ->fillForm(['state_id' => $newPatient->municipality->state_id])
        ->fillForm($newPatient->toArray())
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Patient::class, [
        'first_name' => $newPatient->first_name,
        'last_name' => $newPatient->last_name,
        'email' => $newPatient->email,
        'phone' => $newPatient->phone,
        'address' => $newPatient->address,
    ]);
});

/**
 * Test form input validation for creating a Patient.
 * Ensures that required fields and validations are enforced.
 */
it('can validate input', function () {
    livewire(PatientResource\Pages\CreatePatient::class)
        ->fillForm([
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'phone' => 'invalid-phone',
            'address' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
            'phone' => 'regex',
            'address' => 'required',
        ]);
});

/**
 * Test rendering the Patient edit page.
 * Asserts that the edit page loads successfully for a given patient.
 */
it('can render edit', function () {
    get(PatientResource::getUrl('edit', [Patient::factory()->create()]))->assertSuccessful();
});

/**
 * Test retrieving data for editing a Patient.
 * Ensures that the form is populated with the existing patient's data.
 */
it('can retrieve data', function () {
    $patient = Patient::factory()->create();

    livewire(PatientResource\Pages\EditPatient::class, ['record' => $patient->getRouteKey()])
        ->assertFormSet([
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'email' => $patient->email,
            'phone' => $patient->phone,
            'address' => $patient->address,
        ]);
});

/**
 * Test updating an existing Patient.
 * Ensures that the form can be submitted successfully and the patient data is updated in the database.
 */
it('can update patient', function () {
    $patient = Patient::factory()->create();
    $newPatient = Patient::factory()->make();

    livewire(PatientResource\Pages\EditPatient::class, ['record' => $patient->getRouteKey()])
        ->fillForm($newPatient->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Patient::class, [
        'first_name' => $newPatient->first_name,
        'last_name' => $newPatient->last_name,
        'email' => $newPatient->email,
        'phone' => $newPatient->phone,
        'address' => $newPatient->address,
    ]);
});

/**
 * Test soft deleting a Patient.
 * Ensures that the delete action can be called and the patient is soft deleted.
 */
it('can delete', function () {
    $patient = Patient::factory()->create();

    livewire(PatientResource\Pages\EditPatient::class, ['record' => $patient->getRouteKey()])
        ->callAction('delete');

    assertSoftDeleted($patient);
});
