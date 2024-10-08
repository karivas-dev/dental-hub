<?php

use App\Filament\Resources\ClinicResource;
use App\Models\Clinic;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

// Setup before each test
// Act as an admin user and set the tenant for Filament.
beforeEach(function () {
    $user = User::where('admin', '=', 1)->first();
    $this->actingAs($user);
    Filament::setTenant($user->clinic);
});

/**
 * Test rendering the Clinic index page.
 * Ensures the index page can be accessed and that the table displays the correct records.
 */
it('can render index', function () {
    get(ClinicResource::getUrl('index'))->assertSuccessful();

    livewire(ClinicResource\Pages\ListClinics::class)
        ->assertCanSeeTableRecords(Clinic::where('id', '!=', 1)->limit(5)->get());
});

/**
 * Test rendering the Clinic create page.
 * Asserts that the create page loads successfully.
 */
it('can render create', function () {
    get(ClinicResource::getUrl('create'))->assertSuccessful();
});

/**
 * Test creating a new Clinic.
 * Ensures that the form can be filled and submitted successfully,
 * and the new clinic is stored in the database.
 */
it('can create clinic', function () {
    $newClinic = Clinic::factory()->make();

    livewire(ClinicResource\Pages\CreateClinic::class)
        ->fillForm($newClinic->toArray())
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Clinic::class, [
        'name' => $newClinic->name,
    ]);
});

/**
 * Test form input validation for creating a Clinic.
 * Ensures that required fields and validations are enforced.
 */
it('can validate input', function () {
    livewire(ClinicResource\Pages\CreateClinic::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

/**
 * Test rendering the Clinic edit page.
 * Asserts that the edit page loads successfully for a given clinic.
 */
it('can render edit', function () {
    get(ClinicResource::getUrl('edit', [Clinic::factory()->create()]))->assertSuccessful();
});

/**
 * Test updating an existing clinic.
 * Ensures that the form can be submitted successfully and the clinic data is updated in the database.
 */
it('can update clinic', function () {
    $clinic = Clinic::factory()->create();
    $updatedClinic = Clinic::factory()->make();

    livewire(ClinicResource\Pages\EditClinic::class, ['record' => $clinic->getRouteKey()])
        ->fillForm($updatedClinic->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Clinic::class, [
        'id' => $clinic->id,
        'name' => $updatedClinic->name,
    ]);
});

/**
 * Test soft deleting a clinic.
 * Ensures that the delete action can be called and the clinic is soft deleted.
 */
it('can delete', function () {
    $clinic = Clinic::factory()->create();

    livewire(ClinicResource\Pages\EditClinic::class, ['record' => $clinic->getRouteKey()])
        ->callAction('delete');

    assertSoftDeleted($clinic);
});

/**
 * Test restoring a soft-deleted clinic.
 * Ensures that a clinic can be restored via the restore action.
 */
it('can restore', function () {
    $clinic = Clinic::factory()->trashed()->create();

    livewire(ClinicResource\Pages\EditClinic::class, ['record' => $clinic->getRouteKey()])
        ->callAction('restore');

    assertNotSoftDeleted($clinic);
});
