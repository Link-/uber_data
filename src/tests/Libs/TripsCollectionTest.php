<?php


use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\TripDetails as TripDetails;
use UberCrawler\Libs\TripsCollection as TripsCollection;

class TripsCollectionTest extends TestCase
{
    protected $_tripsCollection;

    protected function setUp()
    {
        $this->_tripsCollection = new TripsCollection();
    }

    protected function tearDown()
    {
        $this->_tripsCollection = null;
    }

    public function testisEmpty()
    {
        if ($this->_tripsCollection->size() == 0) {
            // If Size == 0
            $this->assertTrue($this->_tripsCollection->isEmpty());
        } else {
            // If Size > 0
            $this->assertFalse($this->_tripsCollection->isEmpty());
        }
    }

    public function testAddTrip()
    {
        // After adding a trip, the collection
        // should not be empty
        $this->_tripsCollection->addTrip(new TripDetails());
        $this->assertFalse($this->_tripsCollection->isEmpty());
        // Check if the last insert item is actually
        // a TripDetails Object
        $this->assertInstanceOf(
            TripDetails::class,
            $this->_tripsCollection->current()
        );
    }

    public function testAddTripException()
    {
        $this->expectException(PHPUnit_Framework_Error::class);
        $this->_tripsCollection->addTrip('');
    }
}
