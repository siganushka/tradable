<?php

namespace App\Controller\Admin;

use App\Repository\ProductVariantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VariantController extends AbstractController
{
    /**
     * @Route("/variants", name="admin_variant")
     */
    public function index(ProductVariantRepository $productVariantRepository)
    {
        $entities = $productVariantRepository->findBy([], ['createdAt' => 'DESC', 'id' => 'DESC']);

        return $this->render('admin/variant/index.html.twig', [
            'entities' => $entities,
        ]);
    }
}
