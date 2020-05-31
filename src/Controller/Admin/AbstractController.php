<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Contracts\Translation\TranslatorInterface;

class AbstractController extends BaseController
{
    public function addTransedMessage(string $type, string $messageId, array $parameters = [], string $domain = 'flashes')
    {
        $translator = $this->get('translator');

        $this->addFlash($type, $translator->trans($messageId, $parameters, $domain));
    }

    public static function getSubscribedServices()
    {
        $subscribedServices = parent::getSubscribedServices();
        $subscribedServices['translator'] = TranslatorInterface::class;

        return $subscribedServices;
    }
}
