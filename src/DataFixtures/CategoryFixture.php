<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->generateCategory1($manager);
        $this->generateCategory2($manager);
        $this->generateCategory3($manager);
    }

    private function generateCategory1(ObjectManager $manager)
    {
        $category1 = new Category();
        $category1->setName('数码');

        $category1_1 = new Category();
        $category1_1->setName('手机');
        $category1_1->setParent($category1);

        $category1_1_1 = new Category();
        $category1_1_1->setName('智能手机');
        $category1_1_1->setParent($category1_1);

        $category1_1_2 = new Category();
        $category1_1_2->setName('非智能手机');
        $category1_1_2->setParent($category1_1);

        $category1_1_3 = new Category();
        $category1_1_3->setName('老年机');
        $category1_1_3->setParent($category1_1);

        $category1_2 = new Category();
        $category1_2->setName('电脑');
        $category1_2->setParent($category1);

        $category1_2_1 = new Category();
        $category1_2_1->setName('笔记本');
        $category1_2_1->setParent($category1_2);

        $category1_2_2 = new Category();
        $category1_2_2->setName('台试机');
        $category1_2_2->setParent($category1_2);

        $category1_2_3 = new Category();
        $category1_2_3->setName('平板电脑');
        $category1_2_3->setParent($category1_2);

        $manager->persist($category1);
        $manager->persist($category1_1);
        $manager->persist($category1_1_1);
        $manager->persist($category1_1_2);
        $manager->persist($category1_1_3);
        $manager->persist($category1_2);
        $manager->persist($category1_2_1);
        $manager->persist($category1_2_2);
        $manager->persist($category1_2_3);
        $manager->flush();

        $this->addReference('category1', $category1);
        $this->addReference('category1_1', $category1_1);
        $this->addReference('category1_1_1', $category1_1_1);
        $this->addReference('category1_1_2', $category1_1_2);
        $this->addReference('category1_1_3', $category1_1_3);
        $this->addReference('category1_2', $category1_2);
        $this->addReference('category1_2_1', $category1_2_1);
        $this->addReference('category1_2_2', $category1_2_2);
        $this->addReference('category1_2_3', $category1_2_3);
    }

    private function generateCategory2(ObjectManager $manager)
    {
        $category2 = new Category();
        $category2->setName('服装');

        $category2_1 = new Category();
        $category2_1->setName('男装');
        $category2_1->setParent($category2);

        $category2_1_1 = new Category();
        $category2_1_1->setName('外套');
        $category2_1_1->setParent($category2_1);

        $category2_1_2 = new Category();
        $category2_1_2->setName('T恤');
        $category2_1_2->setParent($category2_1);

        $category2_1_3 = new Category();
        $category2_1_3->setName('卫衣');
        $category2_1_3->setParent($category2_1);

        $category2_1_4 = new Category();
        $category2_1_4->setName('休闲裤');
        $category2_1_4->setParent($category2_1);

        $category2_1_5 = new Category();
        $category2_1_5->setName('牛仔裤');
        $category2_1_5->setParent($category2_1);

        $category2_2 = new Category();
        $category2_2->setName('女装');
        $category2_2->setParent($category2);

        $category2_2_1 = new Category();
        $category2_2_1->setName('羽绒服');
        $category2_2_1->setParent($category2_2);

        $category2_2_2 = new Category();
        $category2_2_2->setName('裙子');
        $category2_2_2->setParent($category2_2);

        $category2_2_3 = new Category();
        $category2_2_3->setName('打底裤');
        $category2_2_3->setParent($category2_2);

        $manager->persist($category2);
        $manager->persist($category2_1);
        $manager->persist($category2_1_1);
        $manager->persist($category2_1_2);
        $manager->persist($category2_1_3);
        $manager->persist($category2_1_4);
        $manager->persist($category2_1_5);
        $manager->persist($category2_2);
        $manager->persist($category2_2_1);
        $manager->persist($category2_2_2);
        $manager->persist($category2_2_3);
        $manager->flush();

        $this->addReference('category2', $category2);
        $this->addReference('category2_1', $category2_1);
        $this->addReference('category2_1_1', $category2_1_1);
        $this->addReference('category2_1_2', $category2_1_2);
        $this->addReference('category2_1_3', $category2_1_3);
        $this->addReference('category2_1_4', $category2_1_4);
        $this->addReference('category2_1_5', $category2_1_5);
        $this->addReference('category2_2', $category2_2);
        $this->addReference('category2_2_1', $category2_2_1);
        $this->addReference('category2_2_2', $category2_2_2);
        $this->addReference('category2_2_3', $category2_2_3);
    }

    private function generateCategory3(ObjectManager $manager)
    {
        $category3 = new Category();
        $category3->setName('家电');

        $category3_1 = new Category();
        $category3_1->setName('电视');
        $category3_1->setParent($category3);

        $category3_2 = new Category();
        $category3_2->setName('空调');
        $category3_2->setParent($category3);

        $category3_3 = new Category();
        $category3_3->setName('洗衣机');
        $category3_3->setParent($category3);

        $manager->persist($category3);
        $manager->persist($category3_1);
        $manager->persist($category3_2);
        $manager->persist($category3_3);
        $manager->flush();

        $this->addReference('category3', $category3);
        $this->addReference('category3_1', $category3_1);
        $this->addReference('category3_2', $category3_2);
        $this->addReference('category3_3', $category3_3);
    }
}
