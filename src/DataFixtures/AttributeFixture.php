<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Attribute;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AttributeFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $attribute1 = new Attribute();
        $attribute1->setName('产地');
        $attribute1->setType('text');
        $attribute1->setConfiguration([
            'min_length' => 2,
            'max_length' => 16,
            'required' => true,
        ]);

        $attribute2 = new Attribute();
        $attribute2->setName('购买需知');
        $attribute2->setType('textarea');
        $attribute2->setConfiguration([
            'rows' => 10,
            'min_length' => 16,
            'max_length' => 255,
            'required' => true,
        ]);

        $attribute3 = new Attribute();
        $attribute3->setName('材质');
        $attribute3->setType('select');
        $attribute3->setConfiguration([
            'choices' => ['金属', '玻璃', '塑料'],
            'required' => true,
        ]);

        $attribute4 = new Attribute();
        $attribute4->setName('性别');
        $attribute4->setType('radio');
        $attribute4->setConfiguration([
            'choices' => ['男', '女', '人妖'],
            'required' => true,
        ]);

        $attribute5 = new Attribute();
        $attribute5->setName('适用年龄');
        $attribute5->setType('checkbox');
        $attribute5->setConfiguration([
            'choices' => ['婴儿', '少年', '青年', '中年', '老年'],
            'min_count' => 1,
            'max_count' => 5,
        ]);

        $attribute6 = new Attribute();
        $attribute6->setName('领型');
        $attribute6->setType('checkbox');
        $attribute6->setConfiguration([
            'choices' => ['方领', '圆领', 'V领', 'U领'],
            'required' => true,
        ]);

        $attribute7 = new Attribute();
        $attribute7->setName('裤型');
        $attribute7->setType('select');
        $attribute7->setConfiguration([
            'choices' => ['长裤', '九分裤', '7分裤', '短裤'],
            'required' => true,
        ]);

        $attribute8 = new Attribute();
        $attribute8->setName('功效');
        $attribute8->setType('radio');
        $attribute8->setConfiguration([
            'choices' => ['去油', '补水', '深度清洁', '祛疤痕'],
            'required' => true,
        ]);

        $manager->persist($attribute1);
        $manager->persist($attribute2);
        $manager->persist($attribute3);
        $manager->persist($attribute4);
        $manager->persist($attribute5);
        $manager->persist($attribute6);
        $manager->persist($attribute7);
        $manager->persist($attribute8);
        $manager->flush();

        $this->addReference('attribute1', $attribute1);
        $this->addReference('attribute2', $attribute2);
        $this->addReference('attribute3', $attribute3);
        $this->addReference('attribute4', $attribute4);
        $this->addReference('attribute5', $attribute5);
        $this->addReference('attribute6', $attribute5);
        $this->addReference('attribute7', $attribute6);
        $this->addReference('attribute8', $attribute7);
    }
}
