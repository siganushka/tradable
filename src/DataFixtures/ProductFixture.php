<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductOption;
use App\Entity\ProductOptionValue;
use App\Entity\ProductVariant;
use BenTools\CartesianProduct\CartesianProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $option1 = new ProductOption();
        $option1->setName('颜色');
        $option1->addValue(new ProductOptionValue('黑色'));
        $option1->addValue(new ProductOptionValue('白色'));
        $option1->addValue(new ProductOptionValue('粉色'));

        $option2 = new ProductOption();
        $option2->setName('存储');
        $option2->addValue(new ProductOptionValue('64GB'));
        $option2->addValue(new ProductOptionValue('128GB'));
        $option2->addValue(new ProductOptionValue('256GB'));

        $option3 = new ProductOption();
        $option3->setName('颜色');
        $option3->addValue(new ProductOptionValue('黑色'));
        $option3->addValue(new ProductOptionValue('白色'));
        $option3->addValue(new ProductOptionValue('粉色'));

        $option4 = new ProductOption();
        $option4->setName('存储');
        $option4->addValue(new ProductOptionValue('64GB'));
        $option4->addValue(new ProductOptionValue('128GB'));
        $option4->addValue(new ProductOptionValue('256GB'));

        $option5 = new ProductOption();
        $option5->setName('尺码');
        $option5->addValue(new ProductOptionValue('均码'));
        $option5->addValue(new ProductOptionValue('XS'));
        $option5->addValue(new ProductOptionValue('S'));
        $option5->addValue(new ProductOptionValue('M'));
        $option5->addValue(new ProductOptionValue('L'));
        $option5->addValue(new ProductOptionValue('XL'));

        $option6 = new ProductOption();
        $option6->setName('版本');
        $option6->addValue(new ProductOptionValue('65 英寸 + 16GB'));
        $option6->addValue(new ProductOptionValue('65 英寸 + 32GB'));
        $option6->addValue(new ProductOptionValue('75 英寸 + 16GB'));
        $option6->addValue(new ProductOptionValue('75 英寸 + 32GB'));

        $product1 = new Product();
        $product1->setCategory($this->getReference('category1_1_1'));
        $product1->setName('Google Pixel 3');
        $product1->setUnit('台');
        $product1->setEnabled(true);
        $product1->addOption($option1);
        $product1->addOption($option2);

        $product2 = new Product();
        $product2->setCategory($this->getReference('category1_1_1'));
        $product2->setName('Google Pixel 3 XL');
        $product2->setUnit('台');
        $product2->setEnabled(true);
        $product2->addOption($option3);
        $product2->addOption($option4);

        $product3 = new Product();
        $product3->setCategory($this->getReference('category2_1_3'));
        $product3->setName('李维斯圆领纯棉长袖卫衣');
        $product3->setUnit('件');
        $product3->setEnabled(true);
        $product3->addOption($option5);

        $product4 = new Product();
        $product4->setCategory($this->getReference('category3_1'));
        $product4->setName('小米 4X 液晶平板电视');
        $product4->setUnit('台');
        $product4->setEnabled(true);
        $product4->addOption($option6);

        $manager->persist($product1);
        $manager->persist($product2);
        $manager->persist($product3);
        $manager->persist($product4);

        $this->generateVariants($product1);
        $this->generateVariants($product2);
        $this->generateVariants($product3);
        $this->generateVariants($product4);

        $manager->flush();

        $this->addReference('product1', $product1);
        $this->addReference('product2', $product2);
        $this->addReference('product3', $product3);
        $this->addReference('product4', $product4);
    }

    private function generateVariants(Product $product)
    {
        $groups = [];
        foreach ($product->getOptions() as $option) {
            foreach ($option->getValues() as $optionValue) {
                $groups[$option->getId()][] = $optionValue;
            }
        }

        foreach (new CartesianProduct($groups) as $optionValues) {
            $variant = new ProductVariant();
            $variant->setPrice(mt_rand(100, 999) * 100);
            $variant->setQuantity(mt_rand(1, 10));
            $variant->setEnabled(true);
            $variant->setOptionValues($optionValues);
            $product->addVariant($variant);
        }
    }
}
