<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Model;

interface ResourceInterface
{
    public function getId(): ?string;

    public function isNew(): bool;

    public function isEqual(?self $target): bool;
}
