<?php

namespace App\Models\Tenants;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperUploadedFile
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
