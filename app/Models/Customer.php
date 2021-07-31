<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sheenazien8\Hascrudactions\Traits\HasLaTable;

/** @package App\Models */
class Customer extends Model
{
    use HasLaTable;

    protected $fillable = ['name', 'email', 'code', 'customer_type_id'];

    /** @return BelongsToMany  */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'customer_group', 'customer_id', 'group_id');
    }

    /** @return HasMany  */
    public function points()
    {
        return $this->hasMany(CustomerPoint::class, 'customer_id');
    }

    /** @return BelongsTo  */
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }
}
