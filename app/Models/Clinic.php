<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Clinic model.
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $website
 * @property string $logo
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 *
 * @mixin IdeHelperClinic
 */
class Clinic extends Model
{
    use HasFactory;

    /**
     * Get the users for the clinic.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'description',
        'status',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
