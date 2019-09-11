<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Api;

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
     * @Route("/categories/{id}", name="api_category_get_item", methods="GET")
     */
    public function getCategoryItem(string $id)
    {
        $entity = $this->categoryRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The category #%s was not found.', $id));
        }

        $context = $this->createSerializationContext();
        $context->addGroup('category');

        $view = $this->view($entity)
            ->setContext($context);

        return $this->handleView($view);
    }

    /**
     * @Route("/categories", name="api_category_get_collection", methods="GET")
     */
    public function getCategoryCollection(Request $request): Response
    {
        $queryBuilder = $this->categoryRepository->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->addOrderBy('c.sort', 'ASC');

        if (null !== $parentId = $request->query->get('parent_id')) {
            $queryBuilder
                ->where('c.parent = :parent')
                ->setParameter('parent', $parentId)
            ;
        }

        $data = [];
        foreach ($queryBuilder->getQuery()->getResult() as $treeNode) {
            array_push($data, [
                'id' => $treeNode->getId(),
                'text' => $treeNode->getName(),
                'children' => !$treeNode->isLeaf(),
                'state' => [
                    'opened' => true,
                    // 'disabled' => true,
                    // 'selected' => true,
                ],
            ]);
        }

        $view = $this->view($data);

        return $this->handleView($view);
    }

    private function buildTree($nodes)
    {
        $data = [];
        foreach ($nodes as $node) {
            $array = [
                'name' => $node->getName(),
            ];

            if (!$node->isLeaf()) {
                $array['value'] = $node->getDepth();
                $array['children'] = self::buildTree($node->getChildren());
            }

            array_push($data, $array);
        }

        return $data;
    }
}
