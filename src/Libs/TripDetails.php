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
  protected $_mapUrl;


  /**
   * [__construct description]
   *
   * @param [type] $date   [description]
   * @param [type] $driver [description]
   * @param [type] $fare   [description]
   * @param [type] $type   [description]
   * @param [type] $city   [description]
   * @param [type] $mapUrl [description]
   */
  public function __construct($date, 
                              $driver, 
                              $fare, 
                              $type, 
                              $city, 
                              $mapUrl) {

    $this->_driverName  = $driver;
    $this->_fareValue   = $fare;
    $this->_type        = $type;
    $this->_city        = $city;
    $this->_mapUrl      = $mapUrl;
    $this->setPickupDate($date);

  }


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
    $this->_pickupDate = DateTime::createFromFormat('m/d/y', $date);

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

    if (empty($date))
      throw new GeneralException("Fare value parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_fareValue = $value;

  }


  /**
   * [setType description]
   *
   * @param [type] $type [description]
   */
  public function setType($type) {

    if (empty($date))
      throw new GeneralException("Type parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_type = $type;

  }


  /**
   * [setCity description]
   *
   * @param [type] $city [description]
   */
  public function setCity($city) {

    if (empty($date))
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
    
    if (empty($date))
      throw new GeneralException("Map URL parameter has to be defined - ". 
                                 "it cannot be empty",
                                 "FATAL");

    $this->_mapUrl = $url;

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
}