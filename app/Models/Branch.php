<?php

namespace App\Models;

use App\Traits\AuthenticatedAndNotAdmin;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class Branch extends Model
{
    use AuthenticatedAndNotAdmin, HasFactory, HasTranslateableModel, SoftDeletes;

    protected static ?string $translateablePackageKey = '';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'main',
        'address',
        'phone',
        'email',
        'clinic_id',
        'municipality_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'main' => 'boolean',
        'clinic_id' => 'integer',
        'municipality_id' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::ApplyOnAuthenticatedAndNotAdmin(function () {
            self::creating(fn(Branch $branch) => $branch->clinic()->associate(Filament::getTenant()));
        });
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
