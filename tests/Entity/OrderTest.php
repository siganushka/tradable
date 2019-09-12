<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Tests\Model;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\ProductItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderTest extends WebTestCase
{
    public function testOrder()
    {
        $item1 = new ProductItem();
        $item1->setName('product item 1');
        $item1->setPrice(10);

        $item2 = new ProductItem();
        $item2->setName('product item 2');
        $item2->setPrice(20);

        $orderItem1 = new OrderItem($item1, 3);
        $orderItem2 = new OrderItem($item2, 5);

        $order = new Order();
        $this->assertCount(0, $order->getItems());
        $this->assertEquals(0, $order->getItemsTotal());
        $this->assertEquals(0, $order->getTotal());

        $order->addItem($orderItem1);
        $order->addItem($orderItem2);
        $this->assertCount(2, $order->getItems());
        $this->assertEquals(130, $order->getItemsTotal());
        $this->assertEquals(130, $order->getTotal());

        $order->removeItem($orderItem1);
        $this->assertCount(1, $order->getItems());
        $this->assertEquals(100, $order->getItemsTotal());
        $this->assertEquals(100, $order->getTotal());

        $order->clearItems();
        $this->assertCount(0, $order->getItems());
        $this->assertEquals(0, $order->getItemsTotal());
        $this->assertEquals(0, $order->getTotal());
    }
}
