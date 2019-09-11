<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Model\SortableInterface;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/categories", name="admin_category", methods="GET|POST")
     * @Route("/categories/new/{parentId}", name="admin_category_new", methods="GET|POST")
     */
    public function index(Request $request, string $parentId = null): Response
    {
        $entity = new Category();
        $entity->setSort(SortableInterface::DEFAULT_SORT);

        if ($parentId && $parent = $this->categoryRepository->find($parentId)) {
            $entity->setParent($parent);
        }

        $form = $this->createForm('App\Form\CategoryType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addTransedMessage('success', 'category.created', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_category_edit', [
                'id' => $entity->getId(),
            ]);
        }

        return $this->render('admin/category/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categories/{id}/edit", name="admin_category_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id): Response
    {
        $entity = $this->categoryRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $form = $this->createForm('App\Form\CategoryType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addTransedMessage('success', 'category.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_category_edit', [
                'id' => $entity->getId(),
            ]);
        }

        return $this->render('admin/category/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categories/{id}/delete", name="admin_category_delete", methods="GET")
     */
    public function delete(string $id): Response
    {
        $entity = $this->categoryRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        if (!$entity->isLeaf()) {
            $this->addTransedMessage('danger', 'category.delete.leaf', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_category');
        }

        if (!$entity->getProducts()->isEmpty()) {
            $this->addTransedMessage('danger', 'category.delete.used', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_category');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addTransedMessage('success', 'category.deleted', [
            '%name%' => $entity->getName(),
        ]);

        return $this->redirectToRoute('admin_category');
    }
}
