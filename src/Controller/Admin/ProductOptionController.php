<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\ProductOption;
use App\Repository\ProductRepository;
use App\Repository\ProductOptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductOptionController extends AbstractController
{
    private $productRepository;
    private $productOptionRepository;

    public function __construct(ProductRepository $productRepository, ProductOptionRepository $productOptionRepository)
    {
        $this->productRepository = $productRepository;
        $this->productOptionRepository = $productOptionRepository;
    }

    /**
     * @Route("/products/{productId}/options", name="admin_product_option", methods="GET|POST")
     */
    public function index(string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        return $this->render('admin/product/option.index.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/products/{productId}/options/new", name="admin_product_option_new", methods="GET|POST")
     */
    public function new(Request $request, string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        $entity = new ProductOption();
        $entity->setProduct($product);

        $form = $this->createForm('App\Form\ProductOptionType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addTransedMessage('success', 'message.product.option.created', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product_option', ['productId' => $productId]);
        }

        return $this->render('admin/product/option.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/options/{id}/edit", name="admin_product_option_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id)
    {
        $entity = $this->productOptionRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $product = $entity->getProduct();

        $form = $this->createForm('App\Form\ProductOptionType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addTransedMessage('success', 'message.product.option.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product_option', ['productId' => $product->getId()]);
        }

        return $this->render('admin/product/option.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/options/{id}/delete", name="admin_product_option_delete", methods="GET")
     */
    public function delete(string $id): Response
    {
        $entity = $this->productOptionRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $product = $entity->getProduct();
        $product->removeOption($entity);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addTransedMessage('success', 'message.product.option.deleted', [
            '%name%' => $entity->getName(),
        ]);

        return $this->redirectToRoute('admin_product_option', ['productId' => $product->getId()]);
    }
}
