<?php

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Warehouse\Observers\RawMaterialObserver;

#[ObservedBy([RawMaterialObserver::class])]
class RawMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
