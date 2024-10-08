<?php

use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

/**
 * Test rendering the User index page.
 * Asserts that the index page can be accessed and that the table displays the correct records for the user's branch.
 */
it('can render index', function () {
    get(UserResource::getUrl('index'))->assertSuccessful();

    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords(User::where('branch_id', Auth::user()->branch_id)->limit(5)->get())
        ->assertCanNotSeeTableRecords(User::where('branch_id', '!=', Auth::user()->branch_id)->limit(5)->get());
});

/**
 * Test rendering the User create page.
 * Asserts that the create page loads successfully.
 */
it('can render create', function () {
    get(UserResource::getUrl('create'))->assertSuccessful();
});

/**
 * Test creating a new User.
 * Ensures that the form can be filled and submitted successfully,
 * and the new user is stored in the database.
 */
it('can create user', function () {
    $newUser = User::factory()->make();

    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm(array_merge($newUser->toArray(), ['password' => 'a2918saajkdb2']))
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(User::class, [
        'name' => $newUser->name,
        'email' => $newUser->email,
        'admin' => $newUser->admin,
        'branch_id' => $newUser->branch_id,
    ]);
});

/**
 * Test form input validation for creating a User.
 * Ensures that required fields and validations are enforced.
 */
it('can validate input', function () {
    livewire(UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'email',
        ]);
});

/**
 * Test rendering the User edit page.
 * Asserts that the edit page loads successfully for a given user.
 */
it('can render edit', function () {
    get(UserResource::getUrl('edit', [
        User::factory()->create([
            'branch_id' => Auth::user()->branch_id,
        ])
    ]))->assertSuccessful();
});

/**
 * Test retrieving data for editing a User.
 * Ensures that the form is populated with the existing user's data.
 */
it('can retrieve data', function () {
    $user = User::factory()->create([
        'branch_id' => Auth::user()->branch_id,
    ]);

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->id])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
            'branch_id' => $user->branch_id,
        ]);
});

/**
 * Test updating an existing User.
 * Ensures that the form can be submitted successfully and the user data is updated in the database.
 */
it('can update user', function () {
    $user = User::factory()->create([
        'branch_id' => Auth::user()->branch_id,
    ]);
    $newUser = User::factory()->make([
        'branch_id' => Auth::user()->branch_id,
    ]);

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->id])
        ->fillForm($newUser->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(User::class, [
        'name' => $newUser->name,
        'email' => $newUser->email,
        'admin' => $newUser->admin,
        'branch_id' => $newUser->branch_id,
    ]);
});

/**
 * Test soft deleting a User.
 * Ensures that the delete action can be called and the user is soft deleted.
 */
it('can delete', function () {
    $user = User::factory()->create([
        'branch_id' => Auth::user()->branch_id,
    ]);

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->id])
        ->callAction('delete');

    assertSoftDeleted($user);
});
