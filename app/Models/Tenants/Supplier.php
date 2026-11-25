<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use App\Traits\Suppliers\HasSupplierForm;
use App\Traits\Suppliers\HasSupplierTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperSupplier
 */
class Supplier extends Model
{
    use BelongsToCompany, HasFactory, HasSupplierForm, HasSupplierTable, SoftDeletes;

    protected $guarded = ['id'];
}
