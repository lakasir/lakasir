<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * @var route
     */
    public $route;

    /**
     * @var method
     */
    public $method;

    /**
     * @var title
     */
    public $title;

    /**
     * @var size
     */
    public $size;

    /**
     * @var card
     */
    public $card;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $method = null, $title, $size = 8, $card = true)
    {
        $this->route = $route;
        $this->method = $method;
        $this->title = $title;
        $this->size = $size;
        $this->card = $card;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form');
    }
}
