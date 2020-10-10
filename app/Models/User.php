<?php

namespace App\Models;

use App\DataTables\UserTable;
use App\Traits\HasLaTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use HasLaTable;
    use HasApiTokens;

    protected $latable = UserTable::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function purchasings()
    {
        return $this->hasMany(Purchasing::class);
    }

    public function getLocalizationAttribute()
    {
        return optional($this->profile ?? 'en')->lang ?? 'en';
    }

    public function adminlte_image()
    {
        return auth()->user()->profile ? media(auth()->user()->profile->media->first()) : config('setting.profile.image_empty');
    }

    public function adminlte_desc()
    {
        return auth()->user()->username;
    }

    public function adminlte_profile_url()
    {
        return 'profile.index';
    }

    /**
     * check is Owner
     *
     * @return bool
     */
    public function getIsOwnerAttribute(): bool
    {
        return $this->getRoleNames()->first() == 'owner';
    }

}
