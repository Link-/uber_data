<?php

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Parser as Parser;
use UberCrawler\Libs\TripsCollection as TripsCollection;

class ParserTest extends TestCase {

  protected $_parser;

  public function setUp() {

    $this->_parser = new Parser();

  }


  public function testgetTripsCollection() {

    $this->assertInstanceOf(TripsCollection::class, 
                            $this->_parser->getTripsCollection());

  }


  /**
   * @dataProvider htmlProvider
   */
  public function testparsePage($html, $expected) {

    // Empty html provided
    $this->assertFalse($this->_parser->parsePage(''));

    // Test good and corrupt HTML
    $this->assertEquals($expected, $this->_parser->parsePage($html));

  }


  public function htmlProvider() {

    // Read the HTML from a sample file
    $file = __DIR__ . DIRECTORY_SEPARATOR . "../_sample_data/sample.html";
    $goodHTML = file_get_contents($file);

    $corruptHTML = <<<EOD
    <html><body><div>Test</div></body></html>
EOD;

    return [
      [$goodHTML, False],
      [$corruptHTML, True]
    ];

  }


  /**
   * @dataProvider paginationProvider
   */
  public function testgetNextPage($html, $expected) {

    $this->_parser->parsePage($html);
    $this->assertEquals($expected, $this->_parser->getNextPage());

  }


  public function paginationProvider() {

    // Read the HTML from a sample file
    $file = __DIR__ . DIRECTORY_SEPARATOR . "../_sample_data/sample.html";
    $goodHTML = file_get_contents($file);

    $corruptHTML = <<<EOD
    <html><body><div>Test</div></body></html>
EOD;

    return [
      [$goodHTML, "?page=2"],
      [$corruptHTML, False]
    ];

  }


  public function testgetInnerHTML() {

    

  }

}