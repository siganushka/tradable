<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Doctrine\EventListener;

use App\Model\SortableInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class SortableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof SortableInterface) {
            return;
        }

        $this->setSortIfNotSet($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof SortableInterface) {
            return;
        }

        $this->setSortIfNotSet($entity);
    }

    private function setSortIfNotSet(SortableInterface $entity)
    {
        if (null === $entity->getSort()) {
            $entity->setSort(SortableInterface::DEFAULT_SORT);
        }
    }
}
