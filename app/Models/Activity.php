<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'ip',
        'info',
        'url',
        'referer',
        'request',
        'browser',
        'property',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
