<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperProductImage
 */
class ProductImage extends Model
{
    use BelongsToCompany, HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPathAttribute()
    {
        $value = $this->attributes['name'];
        $uploadDisk = config('filesystems.upload_disk');
        $driver = config('filesystems.disks.'.$uploadDisk.'.driver');

        if ($driver === 'local' || $driver === 'public') {
            return Storage::disk($uploadDisk)->path($value);
        }

        return Storage::disk($uploadDisk)->url($value);
    }
}
