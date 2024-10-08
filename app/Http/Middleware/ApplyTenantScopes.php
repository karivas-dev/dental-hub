<?php

namespace App\Http\Middleware;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Diagnosis;
use App\Models\EmergencyContact;
use App\Models\MedicRecord;
use App\Models\Patient;
use App\Models\ToothRecord;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->admin) {
            return $next($request);
        }

        Branch::addGlobalScope(fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()));
        User::addGlobalScope(fn(Builder $query) => $query->whereHas('branch',
            fn(Builder $query) => $query->whereBelongsTo(Auth::user()->clinic)
        ));
        Patient::addGlobalScope(fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant()));
        EmergencyContact::addGlobalScope(fn(Builder $query) => $query->whereHas('patient',
            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
        ));
        MedicRecord::addGlobalScope(fn(Builder $query) => $query->whereHas('patient',
            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
        ));
        ToothRecord::addGlobalScope(fn(Builder $query) => $query->whereHas('patient',
            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
        ));
        Appointment::addGlobalScope(fn(Builder $query) => $query->whereHas('branch',
            fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
        ));
        Diagnosis::addGlobalScope(fn(Builder $query) => $query->whereHas('appointment',
            fn(Builder $query) => $query->whereHas('branch',
                fn(Builder $query) => $query->whereBelongsTo(Filament::getTenant())
            )
        ));

        return $next($request);
    }
}
