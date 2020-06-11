<?php

namespace App\Tests\Entity;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\ProductVariant;
use PHPUnit\Framework\TestCase;

class OrderItemTest extends TestCase
{
    public function testOrderItem()
    {
        $product = new Product();
        $product->setName('test product');

        $variant = new ProductVariant();
        $variant->setPrice(50);
        $variant->setProduct($product);

        $orderItem = new OrderItem($variant, 3);

        $this->assertEquals($variant, $orderItem->getVariant());
        $this->assertEquals(50, $orderItem->getPrice());
        $this->assertEquals(3, $orderItem->getQuantity());
        $this->assertEquals(150, $orderItem->getSubtotal());
    }
}
