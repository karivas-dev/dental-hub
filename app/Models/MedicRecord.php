<?php

namespace App\Models;

use App\Enums\Kinship;
use App\Enums\System;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableModel;

class MedicRecord extends Model
{
    use HasFactory, SoftDeletes, HasTranslateableModel;

    protected static ?string $translateablePackageKey = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'details',
        'treatment',
        'hereditary',
        'kinship',
        'system',
        'patient_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'hereditary' => 'boolean',
        'patient_id' => 'integer',
        'kinship' => Kinship::class,
        'system' => System::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
