<?php

namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\TripRoute as TripRoute;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class TripDetails
{
    /**
     * [$_pickupDate description].
     *
     * @var DateTime
     */
    protected $_pickupDate;

    /**
     * [$_driverName description].
     *
     * @var string
     */
    protected $_driverName;

    /**
     * [$_fareValue description].
     *
     * @var string
     */
    protected $_fareValue;

    /**
     * [$_carType description].
     *
     * @var string
     */
    protected $_carType;

    /**
     * [$_city description].
     *
     * @var string
     */
    protected $_city;

    /**
     * [$_mapUrl description].
     *
     * @var string
     */
    protected $_mapUrl = 'N.A';

    /**
     * Each trip has a unit ID this can be used
     * to retrieve further trip information than
     * is available in the rider Dashboard
     *
     * @var string
     */
    protected $_tripID = 'N.A';

    /**
     * TripRoute instance
     *
     * @var TripRoute
     */
    protected $_tripRoute;

    public function __construct()
    {
        $this->_tripRoute = new TripRoute();
    }

    /**
     * PickupDate takes only 1 date format:
     * m/d/y only.
     *
     * @param string $date string date 'm/d/y'
     */
    public function setPickupDate($date)
    {
        if (empty($date)) {
            throw new GeneralException(
                'Date parameter has to be defined - it cannot be empty',
                'FATAL'
            );
        }

        // Handle an exceptional scenario where the uber date
        // is merged with a trip comment when the fare is split
        // TODO:: This should be changed to add the date
        // format in the configuration
        if (strlen($date) > 8) {
            $date = substr($date, 0, 8);
        }

        // Set the default timezone
        date_default_timezone_set(App::$APP_SETTINGS['timezone']);
        // Get a DateTime instance
        $this->_pickupDate = \DateTime::createFromFormat('m/d/y', $date);
        // In case a bad date was provided
        // throw an exception
        $errors = \DateTime::getLastErrors();
        if (!empty($errors['warning_count']) ||
            !empty($errors['error_count'])) {
            throw new GeneralException(
                'Data format provided is not supported - submit an issue '.
                'on Github for a fix',
                'FATAL'
            );
        }
        // Pass the same pickup date for the TripRoute instance
        $this->_tripRoute->setPickupDate($this->_pickupDate);

        return true;
    }

    /**
     * Returns the set _pickupDate.
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getPickupDate()
    {
        return $this->_pickupDate;
    }

    /**
     * [setDriverName description].
     *
     * @codeCoverageIgnore
     *
     * @param [type] $name [description]
     */
    public function setDriverName($name)
    {
        if (empty($name)) {
            throw new GeneralException(
                'Driver Name parameter has to be defined - it cannot be empty',
                'FATAL'
            );
        }

        $this->_driverName = $name;
    }

    /**
     * [setFareValue description].
     *
     * @param [type] $value [description]
     */
    public function setFareValue($value)
    {
        if (empty($value)) {
            // Fares can have an empty value
            // meaning it was a free ride
            $value = 'Free';
        }

        $this->_fareValue = $value;
    }

    /**
     * [getFareValue description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function getFareValue()
    {
        return $this->_fareValue;
    }

    /**
     * [setType description].
     *
     * @codeCoverageIgnore
     *
     * @param [type] $type [description]
     */
    public function setCarType($type)
    {
        if (empty($type)) {
            throw new GeneralException(
                'Type parameter has to be defined - it cannot be empty',
                'FATAL'
            );
        }

        $this->_carType = $type;
    }

    /**
     * [setCity description].
     *
     * @codeCoverageIgnore
     *
     * @param [type] $city [description]
     */
    public function setCity($city)
    {
        if (empty($city)) {
            throw new GeneralException(
                'City parameter has to be defined - it cannot be empty',
                'FATAL'
            );
        }

        $this->_city = $city;
    }

    /**
     * [setMapURL description].
     *
     * @codeCoverageIgnore
     *
     * @param [type] $url [description]
     */
    public function setMapURL($url)
    {
        if (empty($url)) {
            throw new GeneralException(
                'Map URL parameter has to be defined - it cannot be empty',
                'FATAL'
            );
        }

        $this->_mapUrl = $url;
    }

    /**
     * [setTripId description]
     *
     * @param [type] $tripID [description]
     */
    public function setTripId($tripID)
    {
        $this->_tripID = $tripID;
    }

    /**
     * [getTripId description]
     *
     * @return [type] [description]
     */
    public function getTripId()
    {
        return $this->_tripID;
    }

    /**
     * Getter method for the TripRoute instance
     *
     * @return TripRoute TripRoute instance
     */
    public function getTripRoute()
    {
        return $this->_tripRoute;
    }

    /**
     * Return an array of this object's
     * member variables/properties.
     *
     * @return [type] [description]
     */
    public function getTripObjectArray()
    {
        return get_object_vars($this);
    }

    /**
     * Convert the TripDetails Object into an Array.
     *
     * TODO: Replace these checks with getter methods that handle
     * the formatting and edge case values
     *
     * @return array [description]
     */
    public function toArray()
    {
        // Handle scenarios where Date and Time references are not
        // available
        $pickupDate = ($this->_pickupDate) ? $this->_pickupDate->format('Y-m-d') : 'N.A';
        
        $pickupTime = ($this->_tripRoute->getOriginPickupDateTime()) ? $this->_tripRoute->getOriginPickupDateTime()->format('Y-m-d G:i') : 'N.A';

        $dropoffTime = ($this->_tripRoute->getDestDropoffDateTime()) ? $this->_tripRoute->getDestDropoffDateTime()->format('Y-m-d G:i') : 'N.A';

        return [
            $pickupDate,
            $this->_driverName,
            $this->_fareValue,
            $this->_carType,
            $this->_city,
            $this->_tripID,
            $this->_tripRoute->getPickupStreetAddress(),
            $pickupTime,
            $this->_tripRoute->getDropoffStreetAddress(),
            $dropoffTime
        ];
    }
}
