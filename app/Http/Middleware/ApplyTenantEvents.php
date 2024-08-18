<?php

namespace App\Http\Middleware;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantEvents
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->admin) {
            return $next($request);
        }

        Branch::creating(function (Branch $branch) {
            $branch->clinic()->associate(Filament::getTenant());
        });
        User::creating(function (User $user) {
            if (! Auth::user()->branch->main) {
                $user->branch()->associate(Auth::user()->branch);
            }
        });
        Patient::creating(function (Patient $patient) {
            $patient->clinic()->associate(Filament::getTenant());
        });
        Appointment::creating(function (Appointment $appointment) {
            $appointment->branch()->associate(Auth::user()->branch);
        });

        return $next($request);
    }
}
