<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Generator;

use App\Entity\Order;

interface OrderNumberGeneratorInterface
{
    public function generate(Order $order): string;
}
