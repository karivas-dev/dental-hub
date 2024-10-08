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

/**
 * Test rendering the Branch index page.
 * Ensures the index page can be accessed and that the table displays the correct records
 * based on the tenant's clinic association.
 */
it('can render index', function () {
    get(BranchResource::getUrl('index'))->assertSuccessful();

    livewire(BranchResource\Pages\ListBranches::class)
        ->assertCanSeeTableRecords(Branch::whereBelongsTo(Filament::getTenant())->limit(5)->get())
        ->assertCanNotSeeTableRecords(Branch::where('clinic_id', '!=', Filament::getTenant()->id)->limit(5)->get());
});

/**
 * Test rendering the Branch create page.
 * Asserts that the create page loads successfully.
 */
it('can render create', function () {
    get(BranchResource::getUrl('create'))->assertSuccessful();
});

/**
 * Test creating a Branch record.
 * Ensures that the form can be filled and submitted successfully,
 * and the new branch is stored in the database.
 */
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

/**
 * Test form input validation for creating a Branch.
 * Ensures that required fields and validations are enforced.
 */
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

/**
 * Test rendering the Branch view page.
 * Asserts that the view page loads successfully for a given branch.
 */
it('can render view', function () {
    get(BranchResource::getUrl('view', [
        Branch::factory()->recycle(Filament::getTenant())->create(),
    ]))->assertSuccessful();
});

/**
 * Test rendering the Branch edit page.
 * Asserts that the edit page loads successfully for a given branch.
 */
it('can render edit', function () {
    get(BranchResource::getUrl('edit', [
        Branch::factory()->recycle(Filament::getTenant())->create(),
    ]))->assertSuccessful();
});

/**
 * Test retrieving branch data for editing.
 * Ensures that the form is correctly populated with the branch data when editing.
 */
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

/**
 * Test updating an existing branch.
 * Ensures that the form can be submitted successfully and the branch data is updated in the database.
 */
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

/**
 * Test soft deleting a branch.
 * Ensures that the delete action can be called and the branch is soft deleted.
 */
it('can delete', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->create();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->callAction(DeleteAction::class);
});

/**
 * Test bulk deleting branches.
 * Ensures that multiple branches can be soft deleted via a bulk action.
 */
it('can bulk delete', function () {
    $branches = Branch::factory()->recycle(Filament::getTenant())->count(3)->create();

    livewire(BranchResource\Pages\ListBranches::class)
        ->callTableBulkAction(DeleteBulkAction::class, $branches);

    $branches->each(fn($branch) => assertSoftDeleted($branch));
});

/**
 * Test restoring a soft-deleted branch.
 * Ensures that a branch can be restored via the restore action.
 */
it('can restore', function () {
    $branch = Branch::factory()->recycle(Filament::getTenant())->trashed()->create();

    livewire(BranchResource\Pages\EditBranch::class, ['record' => $branch->getRouteKey()])
        ->callAction(RestoreAction::class);

    assertNotSoftDeleted($branch);
});
