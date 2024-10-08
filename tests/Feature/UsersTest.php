<?php

use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render index', function () {
    get(UserResource::getUrl('index'))->assertSuccessful();
    
    livewire(UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords(User::where('branch_id', Auth::user()->branch_id)->limit(5)->get())
        ->assertCanNotSeeTableRecords(User::where('branch_id', '!=', Auth::user()->branch_id)->limit(5)->get());
});

it('can render create', function () {
    get(UserResource::getUrl('create'))->assertSuccessful();
});

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

it('can render edit', function () {
    get(UserResource::getUrl('edit', [
        User::factory()->create([
            'branch_id' => Auth::user()->branch_id,
        ])
    ]))->assertSuccessful();
});

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

it('can delete', function () {
    $user = User::factory()->create([
        'branch_id' => Auth::user()->branch_id,
    ]);

    livewire(UserResource\Pages\EditUser::class, ['record' => $user->id])
        ->callAction('delete');

    assertSoftDeleted($user);
});
