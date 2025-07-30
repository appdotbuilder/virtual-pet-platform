<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserPet
 *
 * @property int $id
 * @property int $user_id
 * @property int $pet_id
 * @property string $custom_name
 * @property string $custom_color
 * @property string|null $custom_accessory
 * @property int $happiness
 * @property int $hunger
 * @property \Illuminate\Support\Carbon|null $last_fed_at
 * @property \Illuminate\Support\Carbon $adopted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Pet $pet
 * @property-read \App\Models\User $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereAdoptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereCustomAccessory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereCustomColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereCustomName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereHappiness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereHunger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereLastFedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet wherePetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPet whereUserId($value)
 * @method static \Database\Factories\UserPetFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class UserPet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'pet_id',
        'custom_name',
        'custom_color',
        'custom_accessory',
        'happiness',
        'hunger',
        'last_fed_at',
        'adopted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_fed_at' => 'datetime',
        'adopted_at' => 'datetime',
        'happiness' => 'integer',
        'hunger' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who owns this pet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pet that was adopted.
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }
}