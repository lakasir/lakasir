<?php

namespace App\Traits;

use App\Helpers\Upload;
use App\Models\Media as MediaModel;
use Symfony\Component\HttpFoundation\File\File;

trait Media
{
    private $file;
    /**
     * Get the image.
     */
    public function image()
    {
        return $this->morphMany(MediaModel::class, 'imageable');
    }

    public function createMediaFromFile($file): void
    {
        if (!$file) return;
        $upload = new Upload($file);
        $uploaded = $upload->action();
        $this->file = $file;
        $this->createMedia($uploaded);
    }

    public function createMedia(File $uploaded): void
    {
        /**
         * TODO: create size and custom_property <sheenazien8 2020-07-05>
         *
         */

        $media = new MediaModel([
            'filename' => $uploaded->getBasename(),
            'location' => $uploaded->getPath(),
            'mime_type' => $uploaded->getMimeType(),
            'orginal_filename' => $this->file->getClientOriginalName(),
        ]);
        $this->image()->save($media);
    }


}
