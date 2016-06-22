<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class TripRoute
{
    /**
     * Main Trip pickup Date -- Same value as what's available
     * in TripDetails. It is passed into this object
     * to create a comprehensive Pickup / Drop-off DateTime object
     *
     * @var timestamp
     */
    protected $_origPickupDateTime;

    /**
     * [$_destDropoffTime description]
     *
     * @var DateTime
     */
    protected $_destDropoffDateTime;

    /**
     * [$_pickupStreetAddress description]
     *
     * @var string
     */
    protected $_pickupStreetAddress;

    /**
     * [$_dropoffStreetAddress description]
     *
     * @var string
     */
    protected $_dropoffStreetAddress;

    /**
     * TripRoute contructor
     *
     * @param \DateTime $pickupDate  Pickup Date
     * @param string    $pickupTime  Pickup Time (string)
     * @param string    $dropoffTime Dropoff Time (string)
     * @param string    $pickupSA    Pickup Street Address
     * @param string    $dropoffSA   Dropoff Street Address
     */
    public function __construct(
        \DateTime $pickupDate,
        $pickupTime = '',
        $dropoffTime = '',
        $pickupSA = '',
        $dropoffSA = ''
    ) {
        $this->setOriginPickupDateTime($pickupTime, $pickupDate);
        $this->setDestDropoffDateTime($pickupTime, $pickupDate);
        $this->setPickupStreetAddress($pickupSA);
        $this->setDropoffStreetAddress($dropoffSA);
    }

    /**
     * Getter method for $_origPickupDateTime
     *
     * @return DateTime Origin Pickup DateTime Object
     */
    public function getOriginPickupDateTime()
    {
        return $this->_origPickupDateTime;
    }

    /**
     * Setter method for the origin pickup time
     * takes as input a time string : HH:mm AM/PM
     * (HH:mm don't stand for php's time format)
     *
     * @param string $timeString Time string of the format HH:mm AM/PM
     */
    public function setOriginPickupDateTime(
        $timeString = '',
        $pickupDate = null
    ) {
        // _pickupDate should not be null
        if (!$pickupDate &&
            !$this->_origPickupDateTime) {
            throw new GeneralException(
                'Pickup Date has to be passed',
                'FATAL'
            );
        }
        // If a pickupDate value is provided
        // set _origPickupDateTime to it
        if ($pickupDate) {
            $this->_origPickupDateTime = $pickupDate;
        }
        // Do nothing else if timeString is not set
        if (empty($timeString)) {
            return;
        }
        // Get the hours and minutes
        $hoursMinutes = $this->getHoursMinutesFromString($timeString);
        // Update the pickupDate
        $this->_origPickupDateTime->setTime(
            $hoursMinutes['hours'],
            $hoursMinutes['minutes']
        );
    }

    /**
     * Getter method for $_destDropoffDateTime
     *
     * @return DateTime Destination Dropoff DateTime Object
     */
    public function getDestDropoffDateTime()
    {
        return $this->_destDropoffDateTime;
    }

    /**
     * [setDestDropoffDateTime description]
     *
     * @param string $timeString  [description]
     * @param Datetime $dropoffDate [description]
     */
    public function setDestDropoffDateTime(
        $timeString = '',
        $dropoffDate = null
    ) {
        if (!$dropoffDate &&
            !$this->_destDropoffDateTime) {
            throw new GeneralException(
                'Dropoff Date has to be passed',
                'FATAL'
            );
        }
        // If a dropoffDate value is provided
        // set _destDropoffDatetime to it
        if ($dropoffDate) {
            $this->_destDropoffDateTime = $dropoffDate;
        }
        // Do nothing else if timeString is not set
        if (empty($timeString)) {
            return;
        }
        // Get the hours and minutes
        $hoursMinutes = $this->getHoursMinutesFromString($timeString);
        // Update the pickupDate
        $this->_destDropoffDateTime->setTime(
            $hoursMinutes['hours'],
            $hoursMinutes['minutes']
        );
    }

    /**
     * Takes time as a String and returns the Hours and Minutes
     *
     * @param string $time Time String
     *
     * @return array Array of Hours as the first item and Minutes as the second
     */
    protected function getHoursMinutesFromString($timeString)
    {
        // Get a timestamp from the timeString argument
        // and reformat it and add it to the DateTime Object
        $timestamp = strtotime($timeString);
        // Handle errors
        if (!$timestamp) {
            throw new GeneralException(
                'Time format provided is invalid',
                'FATAL'
            );
        }
        // Get the hours and minutes
        $hours = date('G', $timestamp);
        $minutes = date('i', $timestamp);

        return [
            'hours' => $hours,
            'minutes' => $minutes
        ];
    }

    /**
     * Setter method for the Pickup Street Address
     *
     * @param string $address Pickup Street Address
     */
    public function setPickupStreetAddress($address = '')
    {
        $this->_pickupStreetAddress = $address;
    }

    /**
     * Getter method for the Pickup Street address
     *
     * @return string Pickup Street Address
     */
    public function getPickupStreetAddress()
    {
        return $this->_pickupStreetAddress;
    }

    /**
     * Setter method for the Dropoff Street address
     *
     * @param string $address Dropoff Street Address
     */
    public function setDropoffStreetAddress($address = '')
    {
        $this->_dropoffStreetAddress = $address;
    }

    /**
     * Getter method for the Dropoff Street Address
     *
     * @return string Dropoff Street Address
     */
    public function getDropoffStreetAddress()
    {
        return $this->_dropoffStreetAddress;
    }
}
