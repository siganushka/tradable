<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

trait EnableTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}
