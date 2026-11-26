<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperNotification
 */
class Notification extends Model
{
    use BelongsToCompany, HasFactory;
}
