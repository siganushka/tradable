<?php

namespace App\Generator;

use App\Entity\Order;

interface OrderNumberGeneratorInterface
{
    public function generate(Order $order): string;
}
