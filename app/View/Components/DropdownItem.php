<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DropdownItem extends Component
{
    /** @var array|object $action */
    public $action;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($action)
    {
        $this->action = $action;
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
