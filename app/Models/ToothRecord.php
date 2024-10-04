<?php

namespace App\Models;

use App\Traits\AuthenticatedAndNotAdmin;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class ToothRecord extends Model
{
    use AuthenticatedAndNotAdmin;
    use HasFactory, HasTranslateableModel;

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
        'date',
        'details',
        'patient_id',
        'tooth_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'patient_id' => 'integer',
        'tooth_id' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function tooth(): BelongsTo
    {
        return $this->belongsTo(Tooth::class);
    }
}
