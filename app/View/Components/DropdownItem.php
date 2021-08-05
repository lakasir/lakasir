<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DropdownItem extends Component
{
    /** @var array|object $option */
    public $option;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($option)
    {
        $this->option = $option;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dropdown-item');
    }
}
