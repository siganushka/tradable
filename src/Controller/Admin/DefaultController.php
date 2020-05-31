<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig');
    }
}
