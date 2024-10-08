<?php

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use Filament\Facades\Filament;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render index', function () {
    get(PatientResource::getUrl('index'))->assertSuccessful();

    livewire(PatientResource\Pages\ListPatients::class)
        ->assertCanSeeTableRecords(Patient::where('clinic_id', Filament::getTenant())->limit(5)->get());
});

it('can render create', function () {
    get(PatientResource::getUrl('create'))->assertSuccessful();
});

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

it('can render edit', function () {
    get(PatientResource::getUrl('edit', [Patient::factory()->create()]))->assertSuccessful();
});

it('can retrieve data', function () {
    $patient = Patient::factory()->create();

    livewire(PatientResource\Pages\EditPatient::class, ['record' => $patient->getRouteKey()])
        ->assertFormSet([
            'name' => $patient->name,
            'email' => $patient->email,
            'phone' => $patient->phone,
            'address' => $patient->address,
        ]);
});

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

it('can delete', function () {
    $patient = Patient::factory()->create();

    livewire(PatientResource\Pages\EditPatient::class, ['record' => $patient->getRouteKey()])
        ->callAction('delete');

    assertSoftDeleted($patient);
});
