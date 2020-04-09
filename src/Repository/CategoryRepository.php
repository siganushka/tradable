<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getRootCategories()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->addOrderBy('c.sort', 'ASC')
            ->addOrderBy('c.id', 'DESC')
            ->getQuery()
        ;

        return $query->getResult();
    }
}
