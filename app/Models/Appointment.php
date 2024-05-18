<?php

namespace App\Models;

use App\Domain\Appointment\AppointmentStatus;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $patient_id
 * @property int $clinic_id
 * @property int $doctor_id
 * @property CarbonImmutable $planned_datetime
 * @property int $planned_duration
 * @property CarbonImmutable $executed_datetime
 * @property string $description
 * @property AppointmentStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Patient $patient
 * @property-read Clinic $clinic
 * @property-read User $doctor
 *
 * @mixin \Illuminate\Database\Eloquent\Builder<Appointment>
 *
 * @method static \Illuminate\Database\Eloquent\Builder<Appointment>|Appointment query()
 */
class Appointment extends Model
{
    use HasFactory;

    const DEFAULT_PLANNED_DURATION = 30;

    protected $fillable = [
        'patient_id',
        'clinic_id',
        'doctor_id',
        'planned_datetime',
        'planned_duration',
        'description',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
        'planned_datetime' => 'datetime:Y-m-d H:i:s',
        'executed_datetime' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @return BelongsTo<Patient, Appointment>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * @return BelongsTo<Clinic, Appointment>
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * @return BelongsTo<User, Appointment>
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
