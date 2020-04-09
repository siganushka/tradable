<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\ProductVariant;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrder()
    {
        $variant1 = new ProductVariant();
        $variant1->setName('product variant 1');
        $variant1->setPrice(10);

        $variant2 = new ProductVariant();
        $variant2->setName('product variant 2');
        $variant2->setPrice(20);

        $orderItem1 = new OrderItem($variant1, 3);
        $orderItem2 = new OrderItem($variant2, 5);

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
