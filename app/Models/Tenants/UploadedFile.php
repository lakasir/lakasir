<?php

namespace App\Models\Tenants;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperUploadedFile
 *
 * @property string $name
 * @property string $original_name
 * @property string $url
 * @property string $mime_type
 * @property string $extension
 * @property string $size
 * @property string $path
 * @property string $relative_path
 * @property string $disk
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
        'relative_path',
        'disk',
    ];

    /**
     * Resolve the storage disk instance for this file.
     * Uses the file's own disk attribute, falling back to config.
     */
    public function resolveDisk(): \Illuminate\Filesystem\FilesystemAdapter
    {
        return Storage::disk($this->disk ?: config('filesystems.upload_disk'));
    }

    /**
     * Get the computed full URL from the relative path and disk.
     * This accessor replaces the old behavior of storing full URLs.
     */
    public function getUrlAttribute(): string
    {
        if ($this->relative_path) {
            return $this->resolveDisk()->url($this->relative_path);
        }

        return $this->attributes['url'] ?? '';
    }

    /**
     * Get the computed absolute filesystem path from the relative path and disk.
     * Only supported on local filesystem drivers; returns empty string for remote disks.
     */
    public function getPathAttribute(): string
    {
        if ($this->relative_path) {
            $disk = $this->resolveDisk();
            $driver = config('filesystems.disks.' . ($this->disk ?: config('filesystems.upload_disk')) . '.driver');
            if ($driver === 'local' || $driver === 'public') {
                return $disk->path($this->relative_path);
            }

            return '';
        }

        return $this->attributes['path'] ?? '';
    }

    /**
     * Move the file from tmp disk to the configured upload disk.
     * Stores a relative path instead of a full URL.
     */
    public function moveToPublic(string $directory, ?string $existingRelativePath = null): string
    {
        $uploadDisk = config('filesystems.upload_disk');
        $tmpDisk = config('filesystems.tmp_disk');

        if (! Storage::disk($tmpDisk)->exists($this->name)) {
            throw new Exception('File in temp directory is not found');
        }

        $relativePath = $directory . '/' . $this->name;
        Storage::disk($uploadDisk)->putFileAs(
            $directory,
            Storage::disk($tmpDisk)->path($this->name),
            $this->name
        );

        if ($existingRelativePath) {
            Storage::disk($uploadDisk)->delete($existingRelativePath);
            static::where('relative_path', $existingRelativePath)->delete();
        }

        Storage::disk($tmpDisk)->delete($this->name);

        $this->update([
            'url' => Storage::disk($uploadDisk)->url($relativePath),
            'relative_path' => $relativePath,
            'disk' => $uploadDisk,
        ]);

        return $relativePath;
    }

    /**
     * Keep backward-compat alias for the misspelled method name.
     */
    public function moveToPuplic(string $directory, ?string $existingRelativePath = null): string
    {
        return $this->moveToPublic($directory, $existingRelativePath);
    }

    /**
     * Delete the file from the upload disk.
     */
    public function deleteFromPublic(string $directory): void
    {
        $uploadDisk = $this->disk ?: config('filesystems.upload_disk');

        if ($this->relative_path) {
            if (Storage::disk($uploadDisk)->exists($this->relative_path)) {
                Storage::disk($uploadDisk)->delete($this->relative_path);
            }
        } elseif ($this->name) {
            Storage::disk($uploadDisk)->delete($directory . '/' . $this->name);
        }

        $this->delete();
    }

    /**
     * Delete the file from the tmp disk.
     */
    public function deleteFromTmp(): void
    {
        $tmpDisk = config('filesystems.tmp_disk');

        if ($this->disk === $tmpDisk || $this->disk === 'tmp') {
            if (Storage::disk($tmpDisk)->exists($this->name)) {
                Storage::disk($tmpDisk)->delete($this->name);
            }
            $this->delete();
        }
    }

    /**
     * Generate the storage URL using the current disk config.
     */
    public function storageUrl(): string
    {
        return $this->resolveDisk()->url($this->relative_path ?: $this->name);
    }

    /**
     * Query scope: find records by relative_path (replaces old scopeInUrl).
     */
    public function scopeInPath($query, $paths)
    {
        return $query->whereIn('relative_path', $paths);
    }

    /**
     * Query scope: backward-compatible — finds by relative_path or URL.
     * During transition, old data still has full URLs in the url column.
     */
    public function scopeInUrl($query, $urls)
    {
        return $query->where(function ($q) use ($urls) {
            $q->whereIn('relative_path', $urls)->orWhereIn('url', $urls);
        });
    }
}
