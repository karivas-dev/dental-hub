<?php

namespace Tests;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
        $this->seed();
        $user = User::where('admin', '=', 0)->first();
        $this->actingAs($user);
        Filament::setTenant($user->clinic);
    }
}
