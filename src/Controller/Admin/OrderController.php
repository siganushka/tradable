<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\Registry;

class OrderController extends AbstractController
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/orders", name="admin_order", methods="GET")
     */
    public function index(): Response
    {
        $entities = $this->orderRepository->findBy([], ['createdAt' => 'DESC', 'id' => 'DESC']);

        return $this->render('admin/order/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/orders/{id}/show", name="admin_order_show", methods="GET")
     */
    public function show(string $id)
    {
        $entity = $this->orderRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        return $this->render('admin/order/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    /**
     * @Route("/orders/{id}/workflow/{transition}", name="admin_order_workflow", methods="GET")
     */
    public function workflow(Registry $workflows, string $id, string $transition = null)
    {
        $entity = $this->orderRepository->find($id);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('The #%s not found.', $id));
        }

        $em = $this->getDoctrine()->getManager();
        if (null === $transition) {
            $entity->setState(Order::STATE_PENDING);
            $em->flush();

            $this->addFlash('success', $this->transMessage('message.order.reseted', [
                'number' => $entity->getNumber(),
            ]));

            return $this->redirectToRoute('admin_order');
        }

        $workflow = $workflows->get($entity);

        try {
            $workflow->apply($entity, $transition);
        } catch (ExceptionInterface $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('admin_order');
        }

        $em->flush();
        $this->addFlash('success', $this->transMessage('message.order.updated', [
            'number' => $entity->getNumber(),
        ]));

        return $this->redirectToRoute('admin_order');
    }
}
