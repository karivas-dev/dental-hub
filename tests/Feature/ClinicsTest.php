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

beforeEach(function () {
    $user = User::where('admin', '=', 1)->first();
    $this->actingAs($user);
    Filament::setTenant($user->clinic);
});

it('can render index', function () {
    get(ClinicResource::getUrl('index'))->assertSuccessful();
});

it('can render create', function () {
    get(ClinicResource::getUrl('create'))->assertSuccessful();
});

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

it('can render edit', function () {
    get(ClinicResource::getUrl('edit', [Clinic::factory()->create()]))->assertSuccessful();
});

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

it('can delete', function () {
    $clinic = Clinic::factory()->create();

    livewire(ClinicResource\Pages\EditClinic::class, ['record' => $clinic->getRouteKey()])
        ->callAction('delete');

    assertSoftDeleted($clinic);
});

it('can restore', function () {
    $clinic = Clinic::factory()->trashed()->create();

    livewire(ClinicResource\Pages\EditClinic::class, ['record' => $clinic->getRouteKey()])
        ->callAction('restore');

    assertNotSoftDeleted($clinic);
});
