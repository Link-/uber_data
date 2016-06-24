<?php

namespace UberCrawler\Tests\Libs;

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Parser as Parser;
use UberCrawler\Libs\TripsCollection as TripsCollection;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class ParserTest extends TestCase
{
    protected $_parser;

    public function setUp()
    {
        $this->_parser = new Parser();
    }

    public function tearDown()
    {
        $this->_parser = null;
    }

    public function testgetTripsCollection()
    {
        $this->assertInstanceOf(
            TripsCollection::class,
            $this->_parser->getTripsCollection()
        );
    }

    /**
     * @dataProvider uberHtmlProvider
     */
    public function testparsePage($html, $expected)
    {
        // Empty html provided
        $this->assertFalse($this->_parser->parsePage(''));

        // Test good and corrupt HTML
        $this->assertEquals($expected, $this->_parser->parsePage($html));
    }

    public function uberHtmlProvider()
    {
        // Read the HTML from a sample file
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/advanced_sample.html';
        $goodHTML = file_get_contents($file);

        $corruptHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        return [
          [$goodHTML, false],
          [$corruptHTML, true],
        ];
    }

    public function testloadHTML()
    {
        // Positive test
        $goodHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        $this->assertTrue($this->_parser->loadHTML($goodHTML));
        $this->assertEquals($goodHTML, $this->_parser->getRawHTMLData());

        // Negative test
        $this->expectException(GeneralException::class);
        $this->_parser->loadHTML('');
    }

    /**
     * @dataProvider paginationProvider
     */
    public function testgetNextPage($html, $expected)
    {
        $this->_parser->parsePage($html);
        $this->assertEquals($expected, $this->_parser->getNextPage());
    }

    public function paginationProvider()
    {
        // Read the HTML from a sample file
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/advanced_sample.html';
        $goodHTML = file_get_contents($file);

        $corruptHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        return [
          [$goodHTML, '?page=2'],
          [$corruptHTML, false],
        ];
    }

    /**
     * Test the Data Table Parser
     * This is an important test and the only way to verify the
     * correctness is by checking the size of the TripsCollection
     * instance because if everything goes well, the TripsCollection
     * will be populated with valid TripDetail instances
     *
     * @dataProvider dataTableProvider
     */
    public function testparseDataTable(
        $html,
        $expectedBool,
        $expectedSize
    ) {
    
        // parseDataTable returns True or False
        // based on whether the TripsCollection
        // instance is empty or not
        $this->_parser->loadHTML($html);
        $this->assertEquals($expectedBool, $this->_parser->parseDataTable());
        $tripsCollection = $this->_parser->getTripsCollection();
        $this->assertEquals($expectedSize, $tripsCollection->size());
        // Test that the first trip's status is canceled
        $firstTrip = $tripsCollection->getTrip(0);
        // We need to make sure that the first trip is actually
        // included in the TripsCollection - else we will get an offset
        // error
        if ($firstTrip) {
            $this->assertEquals('Canceled', $firstTrip->getFareValue());
        }
    }

    public function dataTableProvider()
    {

        // Read the HTML from a sample file
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/advanced_sample.html';
        $goodHTML = file_get_contents($file);

        $corruptHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        return [
          [$goodHTML, false, 20],
          [$corruptHTML, true, 0],
        ];
    }
}
