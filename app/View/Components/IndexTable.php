<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IndexTable extends Component
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var array
     */
    public $thead;

    /**
     * @var string
     */
    public $resources;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, array $thead = [], string $resources)
    {
        $this->title = $title;
        $this->resources = $resources;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.index-table');
    }
}
