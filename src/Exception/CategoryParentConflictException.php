<?php

namespace App\Exception;

use App\Entity\Category;

class CategoryParentConflictException extends \RuntimeException
{
    private $current;
    private $parent;

    public function __construct(Category $current, Category $parent)
    {
        $this->current = $current;
        $this->parent = $parent;

        parent::__construct('The Category Parent Conflict has been detected');
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
