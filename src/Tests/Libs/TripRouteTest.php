<?php

namespace UberCrawler\Tests\Libs;

use PHPUnit\Framework\TestCase;
use UberCrawler\Config\App as App;
use UberCrawler\Libs\TripRoute as TripRoute;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class TripRouteTest extends TestCase
{
    protected $_tripRoute;

    /**
     * The TripRoute object takes a DateTime object
     * as a mandatory argument (pickupDate), this is usually passed
     * from TripDetails. Make sure to mock this value when
     * testing
     */
    public function setUp()
    {
        // Trip Pickup Date
        // Set the default timezone
        date_default_timezone_set(App::$APP_SETTINGS['timezone']);
        $pickupDate = \DateTime::createFromFormat('m/d/y', '01/13/16');
        // Setup a triproute with some params
        $this->_tripRoute = new TripRoute($pickupDate);
    }

    public function tearDown()
    {
        $this->_tripRoute = null;
    }

    /**
     * [testgetOriginPickupDateTime description]
     */
    public function testgetOriginPickupDateTime()
    {
        $this->_tripRoute->setOriginPickupDateTime('05:03 PM');
        $originPickupDT = $this->_tripRoute->getOriginPickupDateTime();
        $this->assertInstanceOf(\DateTime::class, $originPickupDT);
    }

    /**
     * @dataProvider timeProvider
     */
    public function testsetOriginPickupDateTime(
        $time,
        $expected
    ) {
        switch ($expected) {
            // Positive test
            case true:
                // Get the originally set Date
                $controlDateTime = $this->_tripRoute
                                        ->getPickupDate();
                // Add the time
                $this->assertTrue(
                    $this->_tripRoute->setOriginPickupDateTime($time)
                );
                $setDateTime = $this->_tripRoute->getOriginPickupDateTime();
                // Make sure it's a DateTime instance
                $this->assertInstanceOf(\DateTime::class, $setDateTime);
                // Make sure that the set time is equal to the passed
                // argument
                $formattedTime = date('g:i A', strtotime($time));
                $this->assertEquals(
                    $formattedTime,
                    $setDateTime->format('g:i A')
                );
                // Make sure that the date has not changed
                $this->assertEquals(
                    $controlDateTime->format('d-m-Y'),
                    $setDateTime->format('d-m-Y')
                );
                break;
            // Negative test
            case false:
                $this->assertFalse(
                    $this->_tripRoute->setOriginPickupDateTime($time)
                );
                break;
        }
    }

    /**
     * [testgetDestDropoffDateTime description]
     */
    public function testgetDestDropoffDateTime()
    {
        $this->_tripRoute->setDestDropoffDateTime('06:01 PM');
        $dropoffPickupDT = $this->_tripRoute->getDestDropoffDateTime();
        $this->assertInstanceOf(\DateTime::class, $dropoffPickupDT);
    }

    /**
     * @dataProvider timeProvider
     */
    public function testsetDestDropoffDateTime(
        $time,
        $expected
    ) {
        switch ($expected) {
            // Positive test
            case true:
                // Get the originally set Date
                $controlDateTime = $this->_tripRoute
                                        ->getPickupDate();
                // Add the time
                $this->assertTrue(
                    $this->_tripRoute->setDestDropoffDateTime($time)
                );
                $setDateTime = $this->_tripRoute->getDestDropoffDateTime();
                // Make sure it's a DateTime instance
                $this->assertInstanceOf(\DateTime::class, $setDateTime);
                // Make sure that the set time is equal to the passed
                // argument
                $formattedTime = date('g:i A', strtotime($time));
                $this->assertEquals(
                    $formattedTime,
                    $setDateTime->format('g:i A')
                );
                // Make sure that the date has not changed
                $this->assertEquals(
                    $controlDateTime->format('d-m-Y'),
                    $setDateTime->format('d-m-Y')
                );
                break;
            // Negative test
            case false:
                $this->assertFalse(
                    $this->_tripRoute->setDestDropoffDateTime($time)
                );
                break;
        }
    }

    public function timeProvider()
    {
        return [
            ['3:07 PM', true],
            ['03:07 PM', true],
            ['01:07 AM', true],
            ['24:07 PM', false],
            [':07 PM', false],
            ['122:07 AM', false],
        ];
    }

    /**
     * @covers UberCrawler\Libs\TripRoute::getHoursMinutesFromString
     */
    public function testgetHoursMinutesFromString()
    {
        $method = self::getMethod('getHoursMinutesFromString');
        $output = $method->invokeArgs($this->_tripRoute, array('01:01 AM'));
        // Array count
        $this->assertCount(2, $output);
        // Array keys
        $this->assertArrayHasKey('hours', $output);
        $this->assertArrayHasKey('minutes', $output);
        // Array values
        $this->assertContainsOnly('string', $output);
    }

    /**
     * @covers UberCrawler\Libs\TripRoute::setPickupStreetAddress
     * @covers UberCrawler\Libs\TripRoute::getPickupStreetAddress
     */
    public function testsetPickupStreetAddress()
    {
        $input = 'Street Address';
        $this->_tripRoute->setPickupStreetAddress($input);
        $this->assertEquals(
            $input,
            $this->_tripRoute->getPickupStreetAddress()
        );
    }

    /**
     * @covers UberCrawler\Libs\TripRoute::setDropoffStreetAddress
     * @covers UberCrawler\Libs\TripRoute::getDropoffStreetAddress
     */
    public function testsetDropoffStreetAddress()
    {
        $input = 'Random Street';
        $this->_tripRoute->setDropoffStreetAddress($input);
        $this->assertEquals(
            $input,
            $this->_tripRoute->getDropoffStreetAddress()
        );
    }

    /**
     * Using Reflection to test protected methods. Change the accessibility
     * of the method to facilitate the testing
     *
     * @param string $name Method name
     *
     * @return array Array of ReflectionMethod objects
     */
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('UberCrawler\Libs\TripRoute');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
