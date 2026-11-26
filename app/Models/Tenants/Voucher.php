<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVoucher
 */
class Voucher extends Model
{
    use BelongsToCompany, HasFactory;

    protected $guarded = ['id'];
}
