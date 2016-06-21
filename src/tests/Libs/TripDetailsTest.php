<?php

namespace UberCrawler\Tests\Libs;

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\TripDetails as TripDetails;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class TripDetailsTest extends TestCase
{
    protected $_tripDetails;

    protected function setUp()
    {
        $this->_tripDetails = new TripDetails();
    }

    protected function tearDown()
    {
        $this->_tripDetails = null;
    }

    /**
     * @dataProvider dateProvider
     */
    public function testsetPickupDate($dateString)
    {
        // Test Exception
        $this->expectException(GeneralException::class);
        $this->_tripDetails->setPickupDate($dateString);

        // Test Positive case
        $validDate = '01/20/16';
        $this->assertTrue($this->_tripDetails->setPickupDate($validDate));
    }

    public function dateProvider()
    {
        return [
            [
                '2015-2-3',
                '3/3/2016',
                '3-3-2016',
                '23/12/16',
                '23with-Text',
                123,
            ],
        ];
    }

    /**
     * [testEmptySetterExceptions description]
     */
    public function testEmptySetterExceptions()
    {
        $this->expectException(GeneralException::class);
        // Empty pickup Date
        $this->_tripDetails->setPickupDate('');
        $this->_tripDetails->setDriverName('');
        $this->_tripDetails->setCarType('');
        $this->_tripDetails->setCity('');
        $this->_tripDetails->setMapURL('');
        $this->_tripDetails->setTripDetails('');
        $this->_tripDetails->setTripDetails([]);
        $this->_tripDetails->setTripDetails(['1', '2']);
    }

    /**
     * [testsetFareValue description]
     */
    public function testsetFareValue()
    {
        $this->_tripDetails->setFareValue('');
        $this->assertEquals($this->_tripDetails->getFareValue(), 'Free');
    }

    /**
     * @dataProvider tripDetailsProvider
     */
    public function testtoArray(
        $dataArray,
        $expectedArray
    ) {
        // Insert trip data
        $this->_tripDetails->setPickupDate($dataArray[0]);
        $this->_tripDetails->setDriverName($dataArray[1]);
        $this->_tripDetails->setFareValue($dataArray[2]);
        $this->_tripDetails->setCarType($dataArray[3]);
        $this->_tripDetails->setCity($dataArray[4]);
        $this->_tripDetails->setTripId($dataArray[5]);
        $this->_tripDetails
            ->getTripRoute()
            ->setPickupStreetAddress($dataArray[6]);
        $this->_tripDetails
            ->getTripRoute()
            ->setOriginPickupDateTime($dataArray[7]);
        $this->_tripDetails
            ->getTripRoute()
            ->setDropoffStreetAddress($dataArray[8]);
        $this->_tripDetails
            ->getTripRoute()
            ->setDestDropoffDateTime($dataArray[9]);

        $tripArray = $this->_tripDetails->toArray();
        $this->assertInternalType('array', $tripArray);
        $this->assertEquals($tripArray, $expectedArray);
    }

    /**
     * [tripDetailsProvider description]
     */
    public function tripDetailsProvider()
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
