<?php

namespace App\Models;

use App\Enums\Kinship;
use App\Traits\AuthenticatedAndNotAdmin;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class EmergencyContact extends Model
{
    use AuthenticatedAndNotAdmin;
    use HasFactory, HasTranslateableModel, SoftDeletes;

    protected static ?string $translateablePackageKey = '';

    protected static function boot(): void
    {
        parent::boot();

        self::ApplyOnAuthenticatedAndNotAdmin(function () {
            self::addGlobalScope(fn (Builder $query) => $query->whereHas('patient',
                fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
            ));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'cellphone',
        'email',
        'kinship',
        'patient_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'kinship' => Kinship::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
