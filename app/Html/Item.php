<?php

namespace App\Html;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;
use Illuminate\View\View;

/**
 * Class Option
 * @author sheenazien8
 */
class Item extends Fluent implements Arrayable
{
    /**
     * Make a new button instance.
     *
     * @param string|array $options
     * @return static
     */
    public static function make($options = [])
    {
        if (is_string($options)) {
            return new static(['extend' => $options]);
        }

        return new static($options);
    }

    /**
     * Append a class name to column.
     *
     * @param string $class
     * @return $this
     */
    public function addClass($class)
    {
        if (! isset($this->attributes['className'])) {
            $this->attributes['className'] = $class;
        } else {
            $this->attributes['className'] .= " $class";
        }

        return $this;
    }

    /**
     * Set action option value.
     *
     * @param string $value
     * @return $this
     */
    public function url($value)
    {
        $this->attributes['url'] = $value;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function title(string $title)
    {
        $this->attributes['title'] = $title;

        return $this;
    }

    /**
     * @param string $confirm
     * @return $this
     */
    public function confirm(string $confirm)
    {
        $this->attributes['confirm'] = $confirm;

        return $this;
    }

    /**
     * @param string|View $icon
     *
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->attributes['icon'] = $icon;

        return $this;

    }

    public function method(string $method)
    {
        $this->attributes['method'] = $method;

        return $this;
    }

    public function show(bool $show)
    {
        $this->attributes['show'] = $show;

        return $this;
    }
}
