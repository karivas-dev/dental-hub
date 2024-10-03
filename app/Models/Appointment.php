<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as BelongsToThroughTrait;


class Appointment extends Model
{
    use HasFactory, SoftDeletes, HasTranslateableModel;
    use BelongsToThroughTrait;

    protected static ?string $translateablePackageKey = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'details',
        'status',
        'amount',
        'user_id',
        'patient_id',
        'branch_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'user_id' => 'integer',
        'patient_id' => 'integer',
        'branch_id' => 'integer',
        'status' => AppointmentStatus::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function dentalServices(): BelongsToMany
    {
        return $this->belongsToMany(DentalService::class)->withPivot('quantity');
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function clinic(): BelongsToThrough
    {
        return $this->belongsToThrough(Clinic::class, Branch::class);
    }
}
