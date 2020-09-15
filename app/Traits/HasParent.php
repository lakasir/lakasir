<?php

namespace App\Traits;

/**
 * Trait HasParent
 * @author sheenazien
 */
trait HasParent
{
    /**
     * @var Object
     */
    protected Object $parent;
    /**
     * @var string
     */
    protected string $column;

    /**
     * @var string
     */
    protected Object $parentCollection;

    /**
     * @param
     */
    public function __construct()
    {
        $this->parentCollection = collect();
    }

    public function hasParent(string $column, ?Object $parent)
    {
        $this->parent = $parent;
        $this->column = $column;
        $this->collectParent($parent);

        return $this;
    }

    protected function getParent()
    {
        return $this->parent;
    }

    public function collectParent(Object $parent)
    {
        $this->parentCollection->push($this->parent);
    }

    public function getAllParent()
    {
        return $this->parentCollection;
    }
}
