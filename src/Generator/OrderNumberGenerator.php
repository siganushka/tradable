<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Generator;

use App\Entity\Order;

class OrderNumberGenerator implements OrderNumberGeneratorInterface
{
    public function generate(Order $order): string
    {
        $current = new \DateTime();
        $midnight = new \DateTime('midnight');

        $y = $current->format('y');

        $z = $current->format('z');
        $z = str_pad($z, 3, '0', STR_PAD_LEFT);

        $s = $current->getTimestamp() - $midnight->getTimestamp();
        $s = str_pad($s, 5, '0', STR_PAD_LEFT);

        $u = $current->format('u');

        return $y.$z.$s.$u;
    }
}
