<?php namespace UberCrawler\Libs;

use UberCrawler\Libs\TripsCollection as TripsCollection;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

/**
 * 
 */
class Parser {

  /**
   * [$_currentPage description]
   *
   * @var [type]
   */
  protected $_currentPage;
  /**
   * [$_nextPage description]
   *
   * @var [type]
   */
  protected $_nextPage;
  /**
   * [$_rawHTMLData description]
   *
   * @var [type]
   */
  protected $_rawHTMLData;
  /**
   * [$_DomDocument description]
   *
   * @var [type]
   */
  protected $_DomDocument;
  /**
   * [$_DomXPath description]
   *
   * @var [type]
   */
  protected $_DomXPath;
  /**
   * [$_tripsCollection description]
   *
   * @var [type]
   */
  protected $_tripsCollection;

  /**
   * [__construct description]
   *
   * @param string $html [description]
   */
  public function __construct($html = '') {

    $this->_DomDocument = new \DOMDocument;

    if (!empty($html))
      $this->loadHTML($html);

    // Get an instance of TripsCollection Object
    $this->_tripsCollection = new TripsCollection();

  }


  /**
   * [getTripsCollection description]
   *
   * @return [type] [description]
   */
  public function getTripsCollection() {

    return $this->_tripsCollection;

  }


  /**
   * [parsePage description]
   *
   * @param string $html [description]
   *
   * @return [type] [description]
   */
  public function parsePage($html = '') {

    if (empty($html))
      return False;

    $this->loadHTML($html);

    return $this->parseDataTable();

  }

  /**
   * [loadHTML description]
   *
   * @param [type] $html [description]
   *
   * @return [type] [description]
   */
  public function loadHTML($html) {

    if (empty($html))
      throw new GeneralException("Cannot parse empty HTML Document!", 
                                 "FATAL");

    $this->_rawHTMLData = $html;
    $this->_DomDocument->loadHTML($html);
    $this->_DomXPath = new \DomXPath($this->_DomDocument);

  }


  /**
   * [getNextPage description]
   *
   * @return [type] [description]
   */
  public function getNextPage() {

    $nodes = $this->_DomXPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' pagination__next ')]");

    if (count($nodes) > 0) {
      foreach($nodes as $elmnt) {
        // Return the first match
        return $elmnt->attributes['href']->value;
      }
    }

    return False;
  }


  /**
   * [parseDataTable description]
   *
   * @return [type] [description]
   */
  public function parseDataTable() {
    
    // Get the elements with the class name 'trip-expand__origin'
    // the trip details are containing inside those <td> elements
    $nodes = $this->_DomXPath->query("//*[contains(concat(' '," . 
                                     " normalize-space(@class), ' '), '" .
                                     " trip-expand__origin ')]");

    // Parse the elements and get the details
    // the structure is complicated
    // review the Uber details page to understand
    // what's happening here
    foreach($nodes as $node) {
      $tripD = new TripDetails();
      $tripA = [];
      foreach($node->childNodes as $child) {
        if (!empty($child->textContent))
          array_push($tripA, $child->textContent);
      }
      // Set the trip details
      $tripD->setTripDetails($tripA);
      // Add the trip to the Collection
      $this->_tripsCollection->addTrip($tripD);
    }
    
    return $this->_tripsCollection->isEmpty();
  }


  /**
   * [getInnerHTML description]
   *
   * @param [type] $node [description]
   *
   * @return [type] [description]
   */
  protected function getInnerHTML($node) {

    $innerHTML= ''; 
    $children = $node->childNodes; 
    foreach ($children as $child) { 
        $innerHTML .= $child->ownerDocument->saveXML( $child ); 
    } 

    return $innerHTML; 

  }

}