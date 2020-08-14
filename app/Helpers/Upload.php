<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Upload
{
    private $file;
    private $location;
    private $name;
    private $path;

    /**
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->path = 'app/'.now()->format('Y-m-d');
        $this->location = public_path($this->path);
    }

    public function to(string $location)
    {
        return $this;
    }

    public function action()
    {
        $fullname = $this->getFullName();
        $pass = $this->file->move($this->location(), $fullname);

        return $pass;
    }

    public function resize($width, $height)
    {
        /**
         * FIXME: create resize function for mime/type png, jpg <sheenazien8 2020-07-05>
         *
         */

        return $this;
    }

    public function getFullName()
    {
        $name = Str::random() .'.'. $this->file->getClientOriginalExtension();

        return $name;
    }

    public function location()
    {
        return $this->path;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
    }
}
