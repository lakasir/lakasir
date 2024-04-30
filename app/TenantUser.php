<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TenantUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'tenant_id',
        'full_name',
        'email',
        'password',
    ];
}
