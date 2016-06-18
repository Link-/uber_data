<?php

namespace UberCrawler\Libs;

class TripsCollection implements \Iterator
{
      /**
     * [$position description].
     *
     * @var int
     */
    private $position = 0;
    /**
     * [$_trips description].
     *
     * @var array
     */
    private $_trips = array();

    /**
     * [__construct description].
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * [rewind description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * [current description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function current()
    {
        return $this->_trips[$this->position];
    }

    /**
     * [key description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * [next description].
     *
     * @codeCoverageIgnore
     *
     * @return function [description]
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * [valid description].
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function valid()
    {
        return isset($this->_trips[$this->position]);
    }

    /**
     * Returns the size of the _trips 
     * Array.
     *
     * @codeCoverageIgnore
     *
     * @return [type] [description]
     */
    public function size()
    {
        return count($this->_trips);
    }

    /**
     * [isEmpty description].
     *
     * @return bool [description]
     */
    public function isEmpty()
    {
        return empty($this->_trips);
    }

    /**
     * [addTrip description].
     *
     * @param TripDetails $trip [description]
     */
    public function addTrip(TripDetails $trip)
    {
        array_push($this->_trips, $trip);
    }
}
