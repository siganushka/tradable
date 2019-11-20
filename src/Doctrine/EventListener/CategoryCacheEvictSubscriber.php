<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Doctrine\EventListener;

use App\Entity\Category;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CategoryCacheEvictSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Category) {
            return;
        }

        $this->evictCache($args->getEntityManager(), $entity);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Category) {
            return;
        }

        $this->evictCache($args->getEntityManager(), $entity);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Category) {
            return;
        }

        $this->evictCache($args->getEntityManager(), $entity);
    }

    private function evictCache(EntityManagerInterface $em, Category $entity)
    {
        $em->getCache()->evictCollectionRegion(\get_class($entity), 'children');
    }
}
