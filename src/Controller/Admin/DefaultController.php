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
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\RedisStore;
use Symfony\Component\Lock\Store\RetryTillSaveStore;
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
     * @Route("/test_no_lock", name="test_no_lock", methods="GET")
     */
    public function testNoLock(EntityManagerInterface $em)
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
     * @Route("/test_mysql_lock", name="test_mysql_lock", methods="GET")
     */
    public function testMysqlLock(EntityManagerInterface $em)
    {
        $em->beginTransaction();

        try {
            $entity = $em->find('App\Entity\ProductItem', 1, LockMode::PESSIMISTIC_WRITE);
        } catch (\Throwable $th) {
            $em->rollBack();
            $this->logger->debug('商品正在被其它用户操作！');

            return $this->json(['success' => false], 400);
        }

        if ($entity->getQuantity() <= 0) {
            $this->logger->debug(sprintf('商品 #%d 库存不足 %d！', $entity->getId(), $entity->getQuantity()));

            return $this->json(['success' => false], 400);
        }

        // 减库存
        $entity->setQuantity($entity->getQuantity() - 1);
        $em->flush();

        // 必需提交事务，否则其它线程将发生幻读情况
        $em->commit();

        $this->logger->debug(sprintf('商品 #%d 减库存成功，剩余 %d！', $entity->getId(), $entity->getQuantity()));

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/test_symfony_lock", name="test_symfony_lock", methods="GET")
     */
    public function testSymfonyLock(EntityManagerInterface $em)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $store = new RedisStore($redis);
        $store = new RetryTillSaveStore($store);
        $factory = new LockFactory($store);

        $id = 1;
        $lock = $factory->createLock(sprintf('product_%d', $id), 5);
        $lock->acquire(true);

        $entity = $em->find('App\Entity\ProductItem', $id);

        if ($entity->getQuantity() <= 0) {
            // 如果中途任何一步退出，则提前释放锁
            $lock->release();

            $this->logger->debug(sprintf('商品 #%d 库存不足 %d！', $id, $entity->getQuantity()));

            return $this->json(['success' => false], 400);
        }

        // 减库存
        $entity->setQuantity($entity->getQuantity() - 1);
        $em->flush();

        // 更新库存完毕释放锁
        $lock->release();

        $this->logger->debug(sprintf('商品 #%d 减库存成功，剩余 %d！', $id, $entity->getQuantity()));

        return $this->json(['success' => true]);
    }
}
