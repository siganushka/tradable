<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/products", name="admin_product", methods="GET")
     */
    public function index(): Response
    {
        $entities = $this->productRepository->findBy([], ['createdAt' => 'DESC', 'id' => 'DESC']);

        return $this->render('admin/product/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/products/new", name="admin_product_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $entity = new Product();
        $entity->setEnabled(true);

        $form = $this->createForm('App\Form\ProductType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', $this->transMessage('message.product.created', [
                'name' => $entity->getName(),
            ]));

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
            // disable modify
            $this->isProductOptionChanged($entity);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', $this->transMessage('message.product.updated', [
                'name' => $entity->getName(),
            ]));

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

        // $em = $this->getDoctrine()->getManager();
        // $em->remove($entity);
        // $em->flush();

        $this->addFlash('success', $this->transMessage('message.product.deleted', [
            'name' => $entity->getName(),
        ]));

        return $this->redirectToRoute('admin_product');
    }

    private function isProductOptionChanged(Product $product)
    {
        $options = $product->getOptions();
        if ($options->isDirty()) {
            throw new \RuntimeException('Cannot be modify options.');
        }

        $em = $this->getDoctrine()->getManager();
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();

        foreach ($options as $option) {
            if (!empty($uow->getEntityChangeSet($option))) {
                throw new \RuntimeException(sprintf('Cannot be modify options #%d!', $option->getId()));
            }
        }
    }
}
