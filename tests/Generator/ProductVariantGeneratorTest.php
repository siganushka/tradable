<?php

namespace App\Tests\Generator;

use App\Entity\Product;
use App\Entity\ProductOption;
use App\Generator\ProductVariantGenerator;
use PHPUnit\Framework\TestCase;

class ProductVariantGeneratorTest extends TestCase
{
    public function testSomething()
    {
        $option1 = new ProductOption();
        $option1->setName('foo');
        $option1->setChoices(['f1', 'f2', 'f3', 'f1', ' ']);

        $option2 = new ProductOption();
        $option2->setName('bar');
        $option2->setChoices(['b1', 'b2', 'b3']);

        $product = new Product();
        $product->addOption($option1);
        $product->addOption($option2);

        $generator = new ProductVariantGenerator($product);

        $this->assertCount(9, $generator);
        $this->assertInstanceOf(\Traversable::class, $generator);
    }
}
