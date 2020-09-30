<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $animation, $class, $id, $title, $footer, $size;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $animation = null,
        $class = null,
        $id = null,
        $title = null,
        $footer = null,
        $size = 'md'
    )
    {
        $this->animation = $animation;
        $this->class = $class;
        $this->id = $id;
        $this->title = $title;
        $this->footer = $footer;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
