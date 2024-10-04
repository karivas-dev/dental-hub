<?php

namespace App\Models;

use App\Traits\AuthenticatedAndNotAdmin;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosis extends Model
{
    use AuthenticatedAndNotAdmin;
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        self::ApplyOnAuthenticatedAndNotAdmin(function () {
            self::addGlobalScope(fn (Builder $query) => $query->whereHas('appointment',
                fn (Builder $query) => $query->whereHas('branch',
                    fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
                )
            ));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'details',
        'appointment_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'appointment_id' => 'integer',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
