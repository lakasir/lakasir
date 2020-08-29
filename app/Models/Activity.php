<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Activity extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'ip',
        'info',
        'url',
        'referer',
        'request',
        'devices',
        'property',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeGetProperty()
    {
        $property = json_decode($this->property, 1);

        return $property;
    }
}
