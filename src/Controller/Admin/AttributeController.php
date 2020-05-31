<?php

namespace App\Controller\Admin;

use App\Entity\Attribute;
use App\Registry\AttributeTypeRegistry;
use App\Repository\AttributeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttributeController extends AbstractController
{
    private $attributeRepository;

    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @Route("/attributes", name="admin_attribute", methods="GET")
     */
    public function index(AttributeTypeRegistry $registry): Response
    {
        $entities = $this->attributeRepository->findAll();

        return $this->render('admin/attribute/index.html.twig', [
            'entities' => $entities,
            'types' => $registry->keys(),
        ]);
    }

    /**
     * @Route("/attributes/new/{type}", name="admin_attribute_new", methods="GET|POST")
     */
    public function new(Request $request, string $type): Response
    {
        $entity = new Attribute();
        $entity->setType($type);

        $form = $this->createForm('App\Form\AttributeType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addTransedMessage('success', 'attribute.created', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_attribute');
        }

        return $this->render('admin/attribute/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/attributes/{id}/edit", name="admin_attribute_edit", methods="GET|POST")
     */
    public function edit(Request $request, string $id): Response
    {
        $entity = $this->attributeRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $form = $this->createForm('App\Form\AttributeType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addTransedMessage('success', 'attribute.updated', [
                '%name%' => $entity->getName(),
            ]);

            return $this->redirectToRoute('admin_attribute');
        }

        return $this->render('admin/attribute/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/attributes/{id}/delete", name="admin_attribute_delete", methods="GET")
     */
    public function delete(Request $request, string $id): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->query->get('token'))) {
            $this->addTransedMessage('danger', 'csrf_token.invalid');

            return $this->redirectToRoute('admin_attribute');
        }

        $entity = $this->attributeRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        $this->addTransedMessage('success', 'attribute.deleted', [
            '%name%' => $entity->getName(),
        ]);

        return $this->redirectToRoute('admin_attribute');
    }
}
