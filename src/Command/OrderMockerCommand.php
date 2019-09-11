<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Command;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OrderMockerCommand extends Command
{
    protected static $defaultName = 'app:order-mocker';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate mock data for the order (just for debug).')
            ->addArgument('count', InputArgument::OPTIONAL, 'Generate count for mock data.', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');

        $query = $this->entityManager->createQueryBuilder()
            ->select('pi')
            ->from('App\Entity\ProductItem', 'pi')
            ->setMaxResults(5)
            ->getQuery();

        $items = $query->getResult();
        if (!\count($items)) {
            $io->error('Unable to generate order from empty product items.');

            return 0;
        }

        for ($i = 0; $i < $count; ++$i) {
            $rand = mt_rand(0, \count($items) - 1);

            $order = new Order();
            $order->setState(Order::STATE_PENDING);
            for ($j = 0; $j <= $rand; ++$j) {
                $order->addItem(new OrderItem($items[$j], mt_rand(1, 5)));
            }

            $this->entityManager->persist($order);
        }

        $this->entityManager->flush();

        $io->success(sprintf('The %d rows mock data for order has been genearted.', $count));
    }
}
