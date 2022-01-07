<?php

namespace App\Html;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;

/**
 * Class OptionAction
 * @author sheenazien8
 */
class OptionAction extends Fluent implements Arrayable
{
    public static function make($option = [])
    {
        if (is_string($option)) {
            return new static(['option' => $option]);
        }

        return new static($option);
    }

    public function action($value)
    {
        $this->attributes["value"] = $value;

        return $this;
    }

    public function text($text)
    {
        $this->attributes["text"] = $text;

        return $this;
    }

    public function build()
    {
        $this->attributes["html"] = $this->generateOption();

        return $this->getAttributes();
    }

    private function generateOption(): string
    {
        return "<option value=\"".$this->attributes["value"]."\">{$this->attributes["text"]}</option>";
    }

}
