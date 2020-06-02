<?php

namespace App\Doctrine\EventListener;

use App\Entity\Product;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class ProductOptionDisableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::onFlush];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        // $em = $args->getEntityManager();
        // $uow = $em->getUnitOfWork();

        // // $entity = $args->getEntity();
        // dd(
        //     $uow->getScheduledEntityInsertions(),
        //     $uow->getScheduledEntityUpdates(),
        //     $uow->getScheduledEntityDeletions(),
        //     $uow->getScheduledCollectionUpdates(),
        //     $uow->getScheduledCollectionDeletions(),
        // );
        // if (!$entity instanceof Product) {
        //     return;
        // }

        // dd($entity);
    }
}
