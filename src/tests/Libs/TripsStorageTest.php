<?php

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\TripDetails as TripDetails;
use UberCrawler\Libs\TripsStorage as TripsStorage;
use UberCrawler\Libs\TripsCollection as TripsCollection;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class TripsStorageTest extends TestCase {

  protected $_tripCollection;
  protected $_createdFiles = array();

  public function setUp() {

    $this->_tripCollection = new TripsCollection();

  }


  public function tearDown() {

    // Removed created files
    foreach($this->_createdFiles as $file) {
      unlink($file);
    }
    $this->_tripCollection = null;

  }


  /**
   * @dataProvider tripCollectionProvider
   */
  public function testtripCollectiontoCSV($dataArray) {

    // Add TripDetails to the TripsCollection
    $trip = new TripDetails();
    $trip->setTripDetails($dataArray);
    $this->_tripCollection->addTrip($trip);
    // Call TripCollectiontoCSV()
    $createdFile = TripsStorage::TripCollectiontoCSV($this->_tripCollection);
    // Check that the file was created
    $this->assertTrue(file_exists($createdFile));
    // Check that the file size is > 0 bytes
    $this->assertTrue(filesize($createdFile) > 0 ? True : False);
    // Add the created file to the array for removal
    // upon teardown
    array_push($this->_createdFiles, $createdFile);

  }


  public function tripCollectionProvider() {

    // First value of the dataArray is empty
    // to mimic the UI of Uber where the first
    // column in the data table is an empty arrow
    return [
        [['', '03/12/16', 'John', '$10.23', 'UberZ', 'Beirut']],
        [['', '1/1/16', 'John Smith', '$10.23', 'UberZ Platon', 'Moscow Snow']]
    ];

  }

}