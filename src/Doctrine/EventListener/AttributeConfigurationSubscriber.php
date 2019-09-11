<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Doctrine\EventListener;

use App\Entity\Attribute;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Form\Util\FormUtil;

class AttributeConfigurationSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Attribute) {
            return;
        }

        $this->filterConfiguration($args, $entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Attribute) {
            return;
        }

        if (!$args->hasChangedField('configuration')) {
            return;
        }

        $this->filterConfiguration($args, $entity);
    }

    public function filterConfiguration(LifecycleEventArgs $args, Attribute $entity)
    {
        $configuration = [];
        foreach ($entity->getConfiguration() as $key => $value) {
            if (!FormUtil::isEmpty($value)) {
                $configuration[$key] = $value;
            }
        }

        if (isset($configuration['choices'])) {
            $configuration['choices'] = $this->addChoiceIndex($args, $configuration['choices']);
        }

        $entity->setConfiguration($configuration);
    }

    private function addChoiceIndex(LifecycleEventArgs $args, array $choices)
    {
        $indexed = [];
        foreach ($choices as $key => $value) {
            if (!\is_int($key)) {
                $indexed[$key] = $value;
                continue;
            }

            $newKey = $this->generateChoiceIndex($args);
            $indexed[$newKey] = $value;
        }

        return $indexed;
    }

    private function generateChoiceIndex(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();

        $conn = $em->getConnection();
        $sql = 'SELECT '.$conn->getDatabasePlatform()->getGuidExpression();

        return $conn->query($sql)->fetchColumn(0);
    }
}
