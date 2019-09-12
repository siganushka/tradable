<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Tests\Entity;

use App\Entity\OrderItem;
use App\Entity\ProductItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderItemTest extends WebTestCase
{
    public function testOrderItem()
    {
        $item = new ProductItem();
        $item->setName('test');
        $item->setPrice(50);

        $orderItem = new OrderItem($item, 3);

        $this->assertEquals($item, $orderItem->getItem());
        $this->assertEquals(50, $orderItem->getPrice());
        $this->assertEquals(3, $orderItem->getQuantity());
        $this->assertEquals(150, $orderItem->getSubtotal());
    }
}
