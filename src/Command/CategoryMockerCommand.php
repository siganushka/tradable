<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CategoryMockerCommand extends Command
{
    protected static $defaultName = 'app:category-mocker';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate mock data for the category (just for debug).')
            ->addArgument('count', InputArgument::OPTIONAL, 'Generate count for mock data.', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $input->getArgument('count');

        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        if (1 === mt_rand(0, 1)) {
            $mockRoot = new Category();
            $mockRoot->setName(bin2hex(random_bytes(8)));

            $this->entityManager->persist($mockRoot);
            array_push($categories, $mockRoot);

            $io->success('The mock data for root category has been genearted.');
        }

        for ($i = 0; $i < $count; ++$i) {
            $number = array_rand($categories, 1);

            $mockChild = new Category();
            $mockChild->setParent($categories[$number]);
            $mockChild->setName(bin2hex(random_bytes(8)));

            $this->entityManager->persist($mockChild);
            array_push($categories, $mockChild);
        }

        $this->entityManager->flush();

        $io->success(sprintf('The %d rows mock data for category has been genearted.', $count));
    }
}
