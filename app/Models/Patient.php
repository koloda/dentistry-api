<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Patient model.
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property \Carbon\Carbon|\Carbon\CarbonImmutable $date_of_birth
 * @property string $gender
 * @property string $medical_history
 * @property string $allergies
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read Clinic $clinic
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<\App\Models\Patient>
 */
class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'medical_history',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Clinic, \App\Models\Patient>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
