<?php

namespace App\Models\Tenants;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $original_name
 * @property string $url
 * @property string $mime_type
 * @property string $extension
 * @property string $size
 * @property string $path
 * @property string $disk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile inUrl($url)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereUrl($value)
 * @mixin \Eloquent
 */
class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'original_name',
        'url',
        'mime_type',
        'extension',
        'size',
        'path',
        'disk',
    ];

    public function moveToPuplic($path, $existingUrl = null): string
    {
        $tmpFile = $this;
        if ($tmpFile && Storage::disk('tmp')->exists($tmpFile->name)) {
            optional(Storage::disk('public'))->putFileAs($path,
                optional(Storage::disk('tmp'))->path($tmpFile->name), $tmpFile->name
            );
            $tmpFile->update([
                'url' => $url = optional(Storage::disk('public'))->url($path.'/'.$tmpFile->name),
                'path' => optional(Storage::disk('public'))->path($path.'/'.$tmpFile->name),
                'disk' => 'public',
            ]);
            if ($existingUrl) {
                Storage::disk('public')->delete($path.'/'.$existingUrl);
                $this->where('name', $existingUrl)->delete();
            }
            Storage::disk('tmp')->delete($tmpFile->name);

            return $url;
        } else {
            throw new Exception('file in temp dir is not found');
        }
    }

    public function deleteFromPublic($path): void
    {
        if ($this->disk === 'public') {
            Storage::disk('public')->delete($path.'/'.$this->name);
            $this->delete();
        }
    }

    public function deleteFromTmp(): void
    {
        if ($this->disk === 'tmp') {
            Storage::disk('tmp')->delete($this->name);
            $this->delete();
        }
    }

    public function scopeInUrl($query, $url)
    {
        return $query->whereIn('url', $url);
    }
}
