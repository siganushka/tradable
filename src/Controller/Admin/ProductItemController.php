<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\ProductItem;
use App\Repository\ProductItemRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductItemController extends AbstractController
{
    private $productRepository;
    private $productItemRepository;

    public function __construct(ProductRepository $productRepository, ProductItemRepository $productItemRepository)
    {
        $this->productRepository = $productRepository;
        $this->productItemRepository = $productItemRepository;
    }

    /**
     * @Route("/products/{productId}/items", name="admin_product_item", methods="GET")
     */
    public function index(string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        return $this->render('admin/product/item.index.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/products/{productId}/items/new", name="admin_product_item_new", methods="GET|POST")
     */
    public function new(Request $request, string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        $entity = new ProductItem();
        $entity->setProduct($product);
        $entity->setEnabled(true);

        $form = $this->createForm('App\Form\ProductItemType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addTransedMessage('success', 'message.product.item.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product_item', ['productId' => $product->getId()]);
        }

        return $this->render('admin/product/item.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/items/{id}/edit", name="admin_product_item_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id): Response
    {
        $entity = $this->productItemRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $form = $this->createForm('App\Form\ProductItemType', $entity);
        $form->handleRequest($request);

        $product = $entity->getProduct();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addTransedMessage('success', 'message.product.item.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product_item', ['productId' => $product->getId()]);
        }

        return $this->render('admin/product/item.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/items/{id}/delete", name="admin_product_item_delete", methods="GET")
     */
    public function delete(Request $request, string $id): Response
    {
        $entity = $this->productItemRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $product = $entity->getProduct();
        if (!$this->isCsrfTokenValid('delete', $request->query->get('token'))) {
            $this->addTransedMessage('danger', 'csrf_token.invalid');

            return $this->redirectToRoute('admin_product_item', ['productId' => $product->getId()]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addTransedMessage('success', 'product.item.deleted', [
            '%name%' => $entity->getName(),
        ]);

        return $this->redirectToRoute('admin_product_item', ['productId' => $product->getId()]);
    }
}
