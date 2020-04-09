<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Tests\Entity;

use App\Entity\OrderItem;
use App\Entity\ProductVariant;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    public function testOrderItem()
    {
        $variant = new ProductVariant();
        $variant->setName('test');
        $variant->setPrice(50);

        $orderItem = new OrderItem($variant, 3);

        $this->assertEquals($variant, $orderItem->getVariant());
        $this->assertEquals(50, $orderItem->getPrice());
        $this->assertEquals(3, $orderItem->getQuantity());
        $this->assertEquals(150, $orderItem->getSubtotal());
    }
}
