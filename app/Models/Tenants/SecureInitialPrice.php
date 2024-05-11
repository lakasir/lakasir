<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereUserId($value)
 * @mixin \Eloquent
 */
class SecureInitialPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
