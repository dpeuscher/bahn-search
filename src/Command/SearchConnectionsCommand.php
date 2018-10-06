<?php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Dpeuscher\BahnSearch\Service\CheapWeekEndRoundTripService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @category  lib-bahn-search
 * @copyright Copyright (c) 2018 Dominik Peuscher
 */
class SearchConnectionsCommand extends ContainerAwareCommand
{
    /**
     * @var CheapWeekEndRoundTripService
     */
    protected $cheapWeekEndRoundTripService;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected function configure(): void
    {
        $this->setName('search:connections')
            ->addArgument('from', InputArgument::REQUIRED)
            ->addArgument('to', InputArgument::REQUIRED);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->cheapWeekEndRoundTripService = $this->getContainer()->get(CheapWeekEndRoundTripService::class);
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $from = trim($input->getArgument('from'),'"\'');
        $to = trim($input->getArgument('to'), '"\'');

        $roundTrips = $this->cheapWeekEndRoundTripService->getRoundTrips($from, $to);

        foreach ($roundTrips as $roundTrip) {
            echo $roundTrip->getFromDepDateTime()->format('d.m.Y') . ': ' .
                number_format($roundTrip->getFullPrice(), 2, ',',
                    '.') . ' €' . ($roundTrip->getFullPriceFirstClass() !== null ?
                    ', ' . number_format($roundTrip->getFullPriceFirstClass(), 2, ',', '.')
                    . ' €' : '') . "\n";
        }
        $this->entityManager->flush();
    }

}
