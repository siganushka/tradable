<?php

namespace App\Tests\Generator;

use App\Entity\Product;
use App\Entity\ProductOption;
use App\Entity\ProductOptionValue;
use App\Generator\ProductVariantGenerator;
use PHPUnit\Framework\TestCase;

class ProductVariantGeneratorTest extends TestCase
{
    public function testSomething()
    {
        $option1 = new ProductOption();
        $option1->setName('foo');
        $option1->addValue(new ProductOptionValue('f1'));
        $option1->addValue(new ProductOptionValue('f2'));
        $option1->addValue(new ProductOptionValue('f3'));

        $option2 = new ProductOption();
        $option2->setName('bar');
        $option2->addValue(new ProductOptionValue('b1'));
        $option2->addValue(new ProductOptionValue('b2'));
        $option2->addValue(new ProductOptionValue('b3'));

        $product = new Product();
        $product->addOption($option1);
        $product->addOption($option2);

        $generator = new ProductVariantGenerator($product);

        dd($generator);
    }
}
