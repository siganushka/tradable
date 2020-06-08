<?php

namespace App\Tests\Form\DataTransformer;

use App\Entity\ProductOptionValue;
use App\Form\DataTransformer\OptionValueToStringTransformer;
use PHPUnit\Framework\TestCase;

class OptionValueToStringTransformerTest extends TestCase
{
    public function testTransform()
    {
        $optionValues = [
            new ProductOptionValue('A'),
            new ProductOptionValue('B'),
            new ProductOptionValue('C'),
        ];

        $transformer = new OptionValueToStringTransformer();

        $this->assertEquals('A/B/C', $transformer->transform($optionValues));
    }

    public function testReverseTransform()
    {
        $transformer = new OptionValueToStringTransformer();

        $this->assertCount(3, $transformer->reverseTransform('A/B/C'));
    }
}
