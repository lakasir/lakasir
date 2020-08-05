<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'orginal_filename',
        'mime_type',
        'location',
        'custom_property',
        'size'
    ];

    public function getGetFullNameAttribute()
    {
        return $this->location . '/' . $this->fullname;
    }

}
