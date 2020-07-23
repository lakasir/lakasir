<?php

namespace App\Builder;

use App\Helpers\NumberGenerator;

class NumberGeneratorBuilder
{
    /**
     * @var string
     */
    private $model;
    /**
     * @var string
     */
    private $type;

    public function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function prefix(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function build(): NumberGenerator
    {
        return new NumberGenerator(
            $this->model,
            $this->type,
        );
    }
}
