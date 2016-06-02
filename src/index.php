<?php

require_once "vendor/autoload.php";

use UberCrawler\Libs\Crawler as Crawler;
use UberCrawler\Libs\Helper as Helper;
use UberCrawler\Libs\TripsStorage as TripsStorage;

$crawler = new Crawler();
$tripCollection = $crawler->execute();

Helper::printOut("Saving to file");
TripsStorage::TripCollectiontoCSV($crawler->getTripsCollection());