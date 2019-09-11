<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Model;

interface EnableInterface
{
    public function isEnabled(): ?bool;

    public function setEnabled(bool $enabled);
}
