<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VariantController extends AbstractController
{
    /**
     * @Route("/variants", name="admin_variant")
     */
    public function index()
    {
        return $this->render('admin/variant/index.html.twig', [
            'controller_name' => 'VariantController',
        ]);
    }
}
