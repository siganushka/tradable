<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractController extends BaseController
{
    protected function transMessage(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    protected function dispatchEvent(object $event, string $eventName = null)
    {
        return $this->get('dispatcher')->dispatch($event, $eventName);
    }

    public static function getSubscribedServices()
    {
        $subscribedServices = parent::getSubscribedServices();
        $subscribedServices['translator'] = TranslatorInterface::class;
        $subscribedServices['dispatcher'] = EventDispatcherInterface::class;

        return $subscribedServices;
    }
}
