<?php

namespace App\Models;

use App\Enums\Genre;
use App\Traits\AuthenticatedAndNotAdmin;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class Patient extends Model
{
    use AuthenticatedAndNotAdmin;
    use HasFactory, HasTranslateableModel, SoftDeletes;

    protected static ?string $translateablePackageKey = '';

    protected static function boot(): void
    {
        parent::boot();

        self::ApplyOnAuthenticatedAndNotAdmin(function () {
            self::addGlobalScope(fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()));
            self::creating(fn (Patient $patient) => $patient->clinic()->associate(Filament::getTenant()));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dui',
        'email',
        'genre',
        'phone',
        'cellphone',
        'address',
        'occupation',
        'birthdate',
        'municipality_id',
        'clinic_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birthdate' => 'date',
        'municipality_id' => 'integer',
        'clinic_id' => 'integer',
        'genre' => Genre::class,
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function medicRecords(): HasMany
    {
        return $this->hasMany(MedicRecord::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function toothRecords(): HasMany
    {
        return $this->hasMany(ToothRecord::class);
    }
}
