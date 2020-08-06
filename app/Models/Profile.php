<?php

namespace App\Models;

use App\Traits\HasParent;
use App\Traits\Media;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasParent;

    use Media;

    protected $fillable = [
        'phone',
        'bio',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
