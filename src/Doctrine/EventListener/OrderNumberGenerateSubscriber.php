<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Doctrine\EventListener;

use App\Entity\Order;
use App\Generator\OrderNumberGeneratorInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class OrderNumberGenerateSubscriber implements EventSubscriber
{
    private $orderNumberGenerator;

    public function __construct(OrderNumberGeneratorInterface $orderNumberGenerator)
    {
        $this->orderNumberGenerator = $orderNumberGenerator;
    }

    public function getSubscribedEvents()
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Order) {
            return;
        }

        $number = $this->orderNumberGenerator->generate($entity);
        $entity->setNumber($number);
    }
}
