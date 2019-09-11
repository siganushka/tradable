<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Tree;

interface NodeInterface
{
    public function getParent(): ?self;

    public function getChildren(): iterable;

    public function getAncestors(bool $includeSelf = false): iterable;

    public function getSiblings(bool $includeSelf = false): iterable;

    public function getDescendants(bool $includeSelf = false): iterable;

    public function getDepth(): int;

    public function getRoot(): self;

    public function isRoot(): bool;

    public function isLeaf(): bool;
}
