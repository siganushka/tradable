<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductOption;
use App\Entity\ProductOptionValue;
use App\Entity\ProductVariant;
use function BenTools\CartesianProduct\cartesian_product;
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
        $option1->addValue(new ProductOptionValue('65 英寸 + 16GB'));
        $option1->addValue(new ProductOptionValue('65 英寸 + 32GB'));
        $option1->addValue(new ProductOptionValue('75 英寸 + 16GB'));
        $option1->addValue(new ProductOptionValue('75 英寸 + 32GB'));

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
        $option1->addValue(new ProductOptionValue('均码'));
        $option1->addValue(new ProductOptionValue('XS'));
        $option1->addValue(new ProductOptionValue('S'));
        $option1->addValue(new ProductOptionValue('M'));
        $option1->addValue(new ProductOptionValue('L'));
        $option1->addValue(new ProductOptionValue('XL'));

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
        $option1->addValue(new ProductOptionValue('黑色'));
        $option1->addValue(new ProductOptionValue('白色'));
        $option1->addValue(new ProductOptionValue('粉色'));

        $option2 = new ProductOption();
        $option2->setName('存储');
        $option2->addValue(new ProductOptionValue('64GB'));
        $option2->addValue(new ProductOptionValue('128GB'));
        $option2->addValue(new ProductOptionValue('256GB'));

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
        $groups = [];
        foreach ($product->getOptions() as $option) {
            foreach ($option->getValues() as $optionValue) {
                // group by option name (unique)
                $groups[$option->getName()][] = $optionValue;
            }
        }

        foreach (cartesian_product($groups) as $optionValues) {
            $variant = new ProductVariant();
            $variant->setPrice(mt_rand(100, 999) * 100);
            $variant->setQuantity(mt_rand(1, 10));
            $variant->setEnabled(true);
            $variant->setOptionValues($optionValues);
            $product->addVariant($variant);
        }
    }
}
