<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IndexTable extends Component
{
    /** @var string */
    public $title;
    public function __construct(
        string $title = ""
    )
    {
        $this->title = $title;
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
