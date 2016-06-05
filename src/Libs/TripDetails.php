<?php namespace UberCrawler\Libs;

use UberCrawler\Config\App as App;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

/**
 * 
 */
class TripDetails {
  /**
   * [$_pickupDate description]
   *
   * @var [type]
   */
  protected $_pickupDate;
  /**
   * [$_driverName description]
   *
   * @var [type]
   */
  protected $_driverName;
  /**
   * [$_fareValue description]
   *
   * @var [type]
   */
  protected $_fareValue;
  /**
   * [$_carType description]
   *
   * @var [type]
   */
  protected $_carType;
  /**
   * [$_city description]
   *
   * @var [type]
   */
  protected $_city;
  /**
   * [$_mapUrl description]
   *
   * @var [type]
   */
  protected $_mapUrl = 'N.A';


  /**
   * [__construct description]
   */
  public function __construct() { }


  /**
   * [setPickupDate description]
   *
   * @param [type] $date [description]
   */
  public function setPickupDate($date) {

    if (empty($date))
      throw new GeneralException("Date parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    // Set the default timezone
    date_default_timezone_set(App::$APP_SETTINGS['timezone']);
    // Get a DateTime instance
    $this->_pickupDate = \DateTime::createFromFormat('m/d/y', $date);

  }


  /**
   * [setDriverName description]
   *
   * @param [type] $name [description]
   */
  public function setDriverName($name) {

    if (empty($name))
      throw new GeneralException("Driver Name parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_driverName = $name;

  }


  /**
   * [setFareValue description]
   *
   * @param [type] $value [description]
   */
  public function setFareValue($value) {

    if (empty($value)) {
      // Fares can have an empty value
      // meaning it was a free ride
      $value = "Free";
    }

    $this->_fareValue = $value;

  }


  /**
   * [setType description]
   *
   * @param [type] $type [description]
   */
  public function setCarType($type) {

    if (empty($type))
      throw new GeneralException("Type parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_carType = $type;

  }


  /**
   * [setCity description]
   *
   * @param [type] $city [description]
   */
  public function setCity($city) {

    if (empty($city))
      throw new GeneralException("City parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_city = $city;

  }


  /**
   * [setMapURL description]
   *
   * @param [type] $url [description]
   */
  public function setMapURL($url) {
    
    if (empty($url))
      throw new GeneralException("Map URL parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_mapUrl = $url;

  }


  /**
   * [setTripDetails description]
   *
   * @param [type] $details [description]
   */
  public function setTripDetails($details) {

    if (empty($details))
      throw new GeneralException("Trip Details Array has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    if (count($details) < 5)
      throw new GeneralException("Trip Details Array cannot be less ". 
                                 "than 6 items ",
                                 "FATAL");

    // Skip the first element (which is a visual element) 
    // and start from index 1 
    $this->setPickupDate($details[1]);
    $this->setDriverName($details[2]);
    $this->setFareValue(trim($details[3]));
    $this->setCarType($details[4]);
    $this->setCity($details[5]);

  }


  /**
   * Return an array of this object's 
   * member variables/properties
   *
   * @return [type] [description]
   */
  public function getTripArray() {

    return get_object_vars($this);

  }


  /**
   * Convert the TripDetails Object into an Array
   *
   * @return [type] [description]
   */
  public function toArray() {

    return [
      $this->_pickupDate->format('Y-m-d'),
      $this->_driverName,
      $this->_fareValue,
      $this->_carType,
      $this->_city,
      $this->_mapUrl
    ];

  }
}