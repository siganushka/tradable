<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getRootCategories()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
        ;

        return $query->getResult();
    }
}
