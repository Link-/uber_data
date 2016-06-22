<?php

namespace UberCrawler\Tests\Libs;

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\TripDetails as TripDetails;
use UberCrawler\Libs\TripsStorage as TripsStorage;
use UberCrawler\Libs\TripsCollection as TripsCollection;

class TripsStorageTest extends TestCase
{
    protected $_tripCollection;
    
    protected $_createdFiles = array();

    public function setUp()
    {
        $this->_tripCollection = new TripsCollection();
    }

    public function tearDown()
    {
        // Removed created files
        foreach ($this->_createdFiles as $file) {
            unlink($file);
        }
        $this->_tripCollection = null;
    }

    /**
     * @dataProvider tripCollectionProvider
     */
    public function testtripCollectiontoCSV($dataArray)
    {
        // Add TripDetails to the TripsCollection
        $trip = new TripDetails();
        
        // Insert trip data
        $trip->setPickupDate($dataArray[0]);
        $trip->setDriverName($dataArray[1]);
        $trip->setFareValue($dataArray[2]);
        $trip->setCarType($dataArray[3]);
        $trip->setCity($dataArray[4]);
        $trip->setTripId($dataArray[5]);

        $trip->getTripRoute()
            ->setPickupStreetAddress($dataArray[6]);
        $trip->getTripRoute()
            ->setOriginPickupDateTime($dataArray[7]);
        $trip->getTripRoute()
            ->setDropoffStreetAddress($dataArray[8]);
        $trip->getTripRoute()
            ->setDestDropoffDateTime($dataArray[9]);

        $this->_tripCollection->addTrip($trip);
        // Call TripCollectiontoCSV()
        $createdFile = TripsStorage::TripCollectiontoCSV($this->_tripCollection);
        // Check that the file was created
        $this->assertTrue(file_exists($createdFile));
        // Check that the file size is > 0 bytes
        $this->assertTrue(filesize($createdFile) > 0 ? true : false);
        // Add the created file to the array for removal
        // upon teardown
        array_push($this->_createdFiles, $createdFile);
    }

    public function tripCollectionProvider()
    {
        // First value of the dataArray is empty
        // to mimic the UI of Uber where the first
        // column in the data table is an empty arrow
        return [
            [
                ['03/12/16', 'John', '$10.23', 'UberZ', 'Beirut', 'trip_id_123', 'Street Pickup 1', '5:30 PM', 'Street Dropoff 1', '6:03 PM'],
                ['2016-03-12', 'John', '$10.23', 'UberZ', 'Beirut', 'trip_id_123', 'Street Pickup 1', '2016-03-12 17:30', 'Street Dropoff 1', '2016-03-12 18:03']
            ]
        ];
    }
}
