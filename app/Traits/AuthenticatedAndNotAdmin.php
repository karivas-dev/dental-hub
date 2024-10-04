<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthenticatedAndNotAdmin
{
    protected static function ApplyOnAuthenticatedAndNotAdmin(callable $callable): void
    {
        if (! Auth::check() || Auth::user()->admin) {
            return;
        }

        $callable();
    }
}
