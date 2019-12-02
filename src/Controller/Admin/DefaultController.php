<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Controller\Admin;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @Route("/", name="admin_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig');
    }

    /**
     * @Route("/variants_without_lock", name="admin_variants_without_lock", methods="GET")
     */
    public function variantsWithoutLock(EntityManagerInterface $em)
    {
        $entity = $em->getRepository('App\Entity\ProductItem')->find(1);

        if ($entity->getQuantity() <= 0) {
            $this->logger->debug(sprintf('商品 #%d 库存不足 %d！', $entity->getId(), $entity->getQuantity()));

            return $this->json(['success' => false], 400);
        }

        // 减库存
        $entity->setQuantity($entity->getQuantity() - 1);
        $em->flush();

        $this->logger->debug(sprintf('商品 #%d 减库存成功，剩余 %d！', $entity->getId(), $entity->getQuantity()));

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/variants_with_lock", name="admin_variants_with_lock", methods="GET")
     */
    public function variantsWithLock(EntityManagerInterface $em)
    {
        $connection = $em->getConnection();
        $connection->beginTransaction();

        try {
            $entity = $em->getRepository('App\Entity\ProductItem')->find(1, LockMode::PESSIMISTIC_WRITE);
        } catch (\Throwable $th) {
            $connection->rollBack();
            $this->logger->debug(sprintf('商品 #%d 正在被其它用户操作 %d！', $entity->getId(), $entity->getQuantity()));

            return $this->json(['success' => false], 400);
        }

        if ($entity->getQuantity() <= 0) {
            $this->logger->debug(sprintf('商品 #%d 库存不足 %d！', $entity->getId(), $entity->getQuantity()));

            return $this->json(['success' => false], 400);
        }

        // 减库存
        $entity->setQuantity($entity->getQuantity() - 1);
        $em->flush();
        $em->commit(); // 必需提交事务，否则其它线程将发生幻读情况

        $this->logger->debug(sprintf('商品 #%d 减库存成功，剩余 %d！', $entity->getId(), $entity->getQuantity()));

        return $this->json(['success' => true]);
    }
}
