<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;

class AbstractController extends AbstractFOSRestController
{
    protected function handleView(View $view)
    {
        $context = $view->getContext();
        if (!$context->hasAttribute('json_encode_options')) {
            $context->setAttribute('json_encode_options', JSON_UNESCAPED_UNICODE);
        }

        $view->setContext($context);

        return $this->getViewHandler()->handle($view);
    }

    protected function createSerializationContext()
    {
        $context = new Context();
        $context->setGroups(['resource', 'sortable', 'enable', 'versionable', 'timestampable']);

        return $context;
    }
}
