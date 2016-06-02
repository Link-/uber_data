<?php namespace UberCrawler\Libs\Exceptions;

class GeneralException extends \Exception {

  /**
   * [$_type description]
   *
   * @var string
   */
  private $_type = '';


  /**
   * [__construct description]
   *
   * @param string          $message  [description]
   * @param string          $type     [description]
   * @param integer         $code     [description]
   * @param \Exception|null $previous [description]
   */
  public function __construct($message = '',
                              $type = 'FATAL',
                              $code = 0,
                              \Exception $previous = NULL) {

    parent::__construct($message, $code, $previous);
    // Take an exception type
    $this->_type = $type;

  }


  /**
   * [__toString description]
   *
   * @return string [description]
   */
  public function __toString() {
    
    return __CLASS__ . ": [{$this->_type}]::: {$this->message}\n";

  }

}