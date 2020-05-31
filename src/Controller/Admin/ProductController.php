<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    private $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/products", name="admin_product", methods="GET")
     */
    public function index(): Response
    {
        $entities = $this->productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/product/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/products/new/{categoryId}", name="admin_product_new", methods="GET|POST")
     */
    public function new(Request $request, string $categoryId): Response
    {
        $category = $this->categoryRepository->find($categoryId);
        if (!$category) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $entity = new Product();
        $entity->setCategory($category);
        $entity->setEnabled(true);

        $form = $this->createForm('App\Form\ProductType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addTransedMessage('success', 'message.product.created', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/{id}/edit", name="admin_product_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id): Response
    {
        $entity = $this->productRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $form = $this->createForm('App\Form\ProductType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addTransedMessage('success', 'message.product.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products/{id}/delete", name="admin_product_delete", methods="GET")
     */
    public function delete(string $id): Response
    {
        $entity = $this->productRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addTransedMessage('success', 'message.product.deleted', [
            '%name%' => $entity->getName(),
        ]);

        return $this->redirectToRoute('admin_product');
    }
}
