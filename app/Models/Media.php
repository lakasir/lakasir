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

    protected $appends = ['get_full_name'];

    public function getGetFullNameAttribute()
    {
        return $this->location . '/' . $this->filename;
    }
}
