<?php

namespace App\View\Components;

use App\Exceptions\MissingArrayKey;
use Illuminate\View\Component;

class BulkAction extends Component
{
    /** @var array $option */
    private $option = [];

    /**
     * Create a new component instance.
     *
     * @param array $addActions
     * @return void
     */
    public function __construct(array $addActions = [])
    {
        $this->option = $this->mapAddActions($addActions);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.bulk-action');
    }

    /**
     * get array options
     *
     * @return array
     */
    public function getOption()
    {
        $default_option = ['text' => __('app.global.delete'), 'action' => 'ini routes'];
        $options =  array_merge([$default_option], $this->option);

        return $options;
    }

    /**
     * map added actions
     *
     * @param array $option
     * @return array
     */
    private function mapAddActions(array $options)
    {
        $mapped_options = [];
        for ($i = 0; $i < count($options); $i++) {
            if (isset($options[$i])) {
                foreach ($options as $key => $option) {
                    if ((!in_array('text', array_keys($option))) OR (!in_array('action', array_keys($option)))) {
                        throw new MissingArrayKey('index must contain text and action');
                    }
                    $mapped_options[$key] = $option;
                }
            } else {
                if ((!in_array('text', array_keys($options))) OR (!in_array('action', array_keys($options)))) {
                    throw new MissingArrayKey('index must contain text and action');
                }
                $mapped_options[$i] = $options;
                break;
            }
        }

        return $mapped_options;
    }
}
