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

    public function testsetFareValue()
    {
        $this->_tripDetails->setFareValue('');
        $this->assertEquals($this->_tripDetails->getFareValue(), 'Free');
    }

    public function testsetTripDetails()
    {
        $this->expectException(GeneralException::class);
        // Empty array
        $this->_tripDetails->setTripDetails(array());
        // or a number of items less < 5
        $this->_tripDetails->setTripDetails(array(1,2,3));
    }

    public function testgetTripObjectArray()
    {
        $tripObjArray = $this->_tripDetails->getTripObjectArray();
        $this->assertInternalType('array', $tripObjArray);
        $this->assertArrayHasKey('_pickupDate', $tripObjArray);
        $this->assertArrayHasKey('_driverName', $tripObjArray);
        $this->assertArrayHasKey('_fareValue', $tripObjArray);
        $this->assertArrayHasKey('_carType', $tripObjArray);
        $this->assertArrayHasKey('_city', $tripObjArray);
        $this->assertArrayHasKey('_mapUrl', $tripObjArray);
    }

    /**
     * @dataProvider tripDetailsProvider
     */
    public function testtoArray(
        $dataArray,
        $expectedArray
    ) {
    
        $this->_tripDetails->setTripDetails($dataArray);
        $tripArray = $this->_tripDetails->toArray();
        $this->assertInternalType('array', $tripArray);

        for ($i = 0; $i < count($tripArray); ++$i) {
            $this->assertEquals($expectedArray[$i], $tripArray[$i]);
        }
    }

    public function tripDetailsProvider()
    {

        // First value of the dataArray is empty
        // to mimic the UI of Uber where the first
        // column in the data table is an empty arrow
        return [
            [
                ['', '03/12/16', 'John', '$10.23', 'UberZ', 'Beirut'],
                ['2016-03-12', 'John', '$10.23', 'UberZ', 'Beirut', 'N.A'],
            ],
            [
                ['', '1/1/16', 'John Smith', '$10.23', 'UberZ Platon', 'Moscow Snow'],
                ['2016-01-01', 'John Smith', '$10.23', 'UberZ Platon', 'Moscow Snow', 'N.A'],
            ],
        ];
    }
}
