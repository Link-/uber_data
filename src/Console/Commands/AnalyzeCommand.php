<?php

namespace UberCrawler\Console\Commands;

use Exception;
use UberCrawler\Libs\Helper as Helper;
use UberCrawler\Libs\Crawler as Crawler;
use Symfony\Component\Console\Command\Command;
use UberCrawler\Libs\TripsStorage as TripsStorage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Abed Halawi <halawi.abed@gmail.com>
 */
class AnalyzeCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('analyze')
            ->setDescription('Generate analysis data of your Uber rides.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $crawler = new Crawler();
        $tripCollection = $crawler->execute();

        Helper::printOut("Saving to file");
        TripsStorage::TripCollectiontoCSV($crawler->getTripsCollection());
    }
}
