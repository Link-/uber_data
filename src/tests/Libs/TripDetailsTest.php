<?php 

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\TripDetails as TripDetails;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;


class TripDetailsTest extends TestCase {

  protected $_tripDetails;

  protected function setUp() {

    $this->_tripDetails = new TripDetails();

  }

  protected function tearDown() {

    $this->_tripDetails = null;

  }


  /**
   * @dataProvider dateProvider
   */
  public function testsetPickupDate($dateString, $expected) {

    $errors = $this->_tripDetails->setPickupDate($dateString);
    // True & True = no errors
    // otherwise = errors
    $actualState = (empty($errors['warning_count']) & 
                    empty($errors['error_count']));
    $this->assertEquals($expected, $actualState);

  }


  public function dateProvider() {

    return [
      ['2015-2-3', False],
      ['3/3/2016', False],
      ['3-3-2016', False],
      ['23/12/16', False],
      ['23with-Text', False],
      [123, False],
      ['10/23/16', True]
    ];

  }


  public function testEmptySetterExceptions() {

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


  public function testsetFareValueFree() {

    $this->_tripDetails->setFareValue('');
    $this->assertEquals($this->_tripDetails->getFareValue(), 'Free');

  }


  public function testgetTripObjectArray() {

    $tripObjArray = $this->_tripDetails->getTripObjectArray();
    $this->assertInternalType('array', $tripObjArray);
    $this->assertArrayHasKey('_pickupDate', $tripObjArray);
    $this->assertArrayHasKey('_driverName', $tripObjArray);
    $this->assertArrayHasKey('_fareValue', $tripObjArray);
    $this->assertArrayHasKey('_carType', $tripObjArray);
    $this->assertArrayHasKey('_city', $tripObjArray);
    $this->assertArrayHasKey('_mapUrl', $tripObjArray);

  }


  public function toArray() {

    $tripArray = $this->_tripDetails->toArray();
    $this->assertInternalType('array', $tripArray);
    $this->assertArrayHasKey('_pickupDate', $tripObjArray);
    $this->assertArrayHasKey('_driverName', $tripObjArray);
    $this->assertArrayHasKey('_fareValue', $tripObjArray);
    $this->assertArrayHasKey('_carType', $tripObjArray);
    $this->assertArrayHasKey('_mapUrl', $tripObjArray);

  }

}