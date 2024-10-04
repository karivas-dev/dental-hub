<?php

use App\Filament\Resources\BranchResource;
use App\Models\Branch;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render index', function () {
    get(BranchResource::getUrl('index'))->assertSuccessful();
});

it('can render create', function () {
    get(BranchResource::getUrl('create'))->assertSuccessful();
});

it('can create branch', function () {
    $newBranch = Branch::factory()->make();

    livewire(BranchResource\Pages\CreateBranch::class)
        ->fillForm($newBranch->toArray())
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Branch::class, [
        'name' => $newBranch->name,
        'address' => $newBranch->address,
        'phone' => $newBranch->phone,
        'email' => $newBranch->email,
    ]);
});

it('can validate input', function () {
    livewire(BranchResource\Pages\CreateBranch::class)
        ->fillForm([
            'name' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);
});

it('can render view', function () {
    get(BranchResource::getUrl('view', [
        Branch::factory()->recycle(Filament::getTenant())->create(),
    ]))->assertSuccessful();
});

it('can render edit', function () {
    get(BranchResource::getUrl('edit', [
        Branch::factory()->recycle(Filament::getTenant())->create(),
    ]))->assertSuccessful();
});

it('can retrieve data', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->create();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->assertFormSet([
            'name' => $branch->name,
            'address' => $branch->address,
            'phone' => $branch->phone,
            'email' => $branch->email,
        ]);
});

it('can update branch', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->create();
    $newBranch = Branch::factory()->recycle(Filament::getTenant())->make();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->fillForm($newBranch->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Branch::class, [
        'name' => $newBranch->name,
        'address' => $newBranch->address,
        'phone' => $newBranch->phone,
        'email' => $newBranch->email,
    ]);
});

it('can delete', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->create();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->callAction(DeleteAction::class);
});

it('can bulk delete', function () {
    $branches = Branch::factory()->recycle(Filament::getTenant())->count(3)->create();

    livewire(BranchResource\Pages\ListBranches::class)
        ->callTableBulkAction(DeleteBulkAction::class, $branches);

    $branches->each(fn($branch) => assertSoftDeleted($branch));
});

it('can restore', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->trashed()->create();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->callAction(RestoreAction::class);

    assertNotSoftDeleted($branch);
});

//it('can bulk restore', function () {
//    $branches = Branch::factory(2)->recycle(Filament::getTenant())->trashed()->count(3)->create();
//
//    livewire(BranchResource\Pages\ListBranches::class)
//        ->callTableBulkAction(RestoreBulkAction::class, $branches);
//
//    $branches->each(fn($branch) => assertNotSoftDeleted($branch));
//});
