<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Model;

interface SortableInterface
{
    const DEFAULT_SORT = 255;

    public function getSort(): ?int;

    public function setSort(int $sort);
}
