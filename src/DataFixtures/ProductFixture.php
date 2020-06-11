<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductOption;
use App\Entity\ProductVariant;
use App\Generator\ProductVariantGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->generateProduct1($manager);
        $this->generateProduct2($manager);
        $this->generateProduct3($manager);
    }

    private function generateProduct1(ObjectManager $manager)
    {
        $option1 = new ProductOption();
        $option1->setName('版本');
        $option1->setChoices(['16GB+65寸', '32GB+65寸', '16GB+75寸', '32GB+75寸']);

        $product = new Product();
        $product->setName('小米 4X 液晶平板电视');
        $product->setUnit('台');
        $product->setEnabled(true);
        $product->addOption($option1);

        $this->generateVariants($product);

        $manager->persist($product);
        $manager->flush();

        $this->addReference('product1', $product);
    }

    private function generateProduct2(ObjectManager $manager)
    {
        $option1 = new ProductOption();
        $option1->setName('尺码');
        $option1->setChoices(['均码', 'XS', 'S', 'M', 'L', 'XL']);

        $product = new Product();
        $product->setName('李维斯圆领纯棉长袖卫衣');
        $product->setUnit('件');
        $product->setEnabled(true);
        $product->addOption($option1);

        $this->generateVariants($product);

        $manager->persist($product);
        $manager->flush();

        $this->addReference('product2', $product);
    }

    private function generateProduct3(ObjectManager $manager)
    {
        $option1 = new ProductOption();
        $option1->setName('颜色');
        $option1->setChoices(['黑色', '白色', '粉色']);

        $option2 = new ProductOption();
        $option2->setName('存储');
        $option2->setChoices(['64GB', '128GB', '256GB']);

        $product = new Product();
        $product->setName('Google Pixel 3');
        $product->setUnit('台');
        $product->setEnabled(true);
        $product->addOption($option1);
        $product->addOption($option2);

        $this->generateVariants($product);

        $manager->persist($product);
        $manager->flush();

        $this->addReference('product3', $product);
    }

    private function generateVariants(Product $product)
    {
        foreach (new ProductVariantGenerator($product) as $key => $value) {
            $variant = new ProductVariant();
            $variant->setPrice(mt_rand(100, 999) * 100);
            $variant->setInventory(mt_rand(1, 10));
            $variant->setEnabled(true);
            $variant->setOptionChoiceKey($key);
            $product->addVariant($variant);
        }
    }
}
