<?php

namespace App\Controller\Admin;

use App\Entity\ProductVariant;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductVariantController extends AbstractController
{
    private $productRepository;
    private $productVariantRepository;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * @Route("/products/{productId}/variants", name="admin_product_variant", methods="GET")
     */
    public function index(string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        return $this->render('admin/product/variant.index.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/products/{productId}/variants/new", name="admin_product_variant_new", methods="GET|POST")
     */
    public function new(Request $request, string $productId): Response
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $productId));
        }

        $entity = new ProductVariant();
        $entity->setProduct($product);
        $entity->setEnabled(true);

        $form = $this->createForm('App\Form\ProductVariantType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', $this->transMessage('message.product.variant.updated', [
                'name' => $entity->getOptionValuesNames(),
            ]));

            return $this->redirectToRoute('admin_product_variant', ['productId' => $product->getId()]);
        }

        return $this->render('admin/product/variant.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/variants/{id}/edit", name="admin_product_variant_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id): Response
    {
        $entity = $this->productVariantRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $form = $this->createForm('App\Form\ProductVariantType', $entity);
        $form->handleRequest($request);

        $product = $entity->getProduct();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $this->transMessage('message.product.variant.updated', [
                'name' => $entity->getOptionValuesNames(),
            ]));

            return $this->redirectToRoute('admin_product_variant', ['productId' => $product->getId()]);
        }

        return $this->render('admin/product/variant.form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/variants/{id}/delete", name="admin_product_variant_delete", methods="GET")
     */
    public function delete(Request $request, string $id): Response
    {
        $entity = $this->productVariantRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $product = $entity->getProduct();
        if (!$this->isCsrfTokenValid('delete', $request->query->get('token'))) {
            $this->addFlash('danger', $this->transMessage('csrf_token.invalid'));

            return $this->redirectToRoute('admin_product_variant', ['productId' => $product->getId()]);
        }

        // $em = $this->getDoctrine()->getManager();
        // $em->remove($entity);
        // $em->flush();

        $this->addFlash('success', $this->transMessage('product.variant.deleted', [
            'name' => $entity->getOptionValuesNames(),
        ]));

        return $this->redirectToRoute('admin_product_variant', ['productId' => $product->getId()]);
    }
}
