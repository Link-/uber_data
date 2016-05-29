<?php namespace UberCrawler\Libs;

use UberCrawler\Libs\TripDetails as TripDetails;

/**
 * 
 */
class TripsCollection implements \Iterator {

	/**
	 * [$position description]
	 *
	 * @var integer
	 */
	private $position = 0;
	/**
	 * [$_trips description]
	 *
	 * @var array
	 */
	private $_trips = array();


	/**
	 * [__construct description]
	 */
	public function __construct() {
	  
	  $this->position = 0;

	}


	/**
	 * [rewind description]
	 *
	 * @return [type] [description]
	 */
  public function rewind() {

      $this->position = 0;
  }


  /**
   * [current description]
   *
   * @return [type] [description]
   */
  public function current() {

      return $this->_trips[$this->position];
  }


  /**
   * [key description]
   *
   * @return [type] [description]
   */
  public function key() {

      return $this->position;
  }


  /**
   * [next description]
   *
   * @return function [description]
   */
  public function next() {

      ++$this->position;
  }


  /**
   * [valid description]
   *
   * @return [type] [description]
   */
  public function valid() {

      return isset($this->_trips[$this->position]);
  }


  /**
   * [isEmpty description]
   *
   * @return boolean [description]
   */
  public function isEmpty() {

  	return empty($this->_trips);

  }

  /**
   * [addTrip description]
   *
   * @param TripDetails $trip [description]
   */
  public function addTrip(TripDetails $trip) {

  	array_push($this->_trips, $trip);

  }

}