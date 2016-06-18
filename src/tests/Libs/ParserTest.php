<?php

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
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/sample.html';
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
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/sample.html';
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
    }

    public function dataTableProvider()
    {

        // Read the HTML from a sample file
        $file = __DIR__.DIRECTORY_SEPARATOR.'../_sample_data/sample.html';
        $goodHTML = file_get_contents($file);

        $corruptHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        return [
          [$goodHTML, false, 20],
          [$corruptHTML, true, 0],
        ];
    }

    /**
     * @dataProvider htmlProvider
     */
    public function testgetInnerHTML(
        $html,
        $parentId,
        $element,
        $numb,
        $innerHTML
    ) {
    

        // Load the HTML, then retrieve the instance
        // of DomDocument and finally call the getInnerHTML
        // on the element
        $this->_parser->loadHTML($html);
        $dom = $this->_parser->getDomDocument();
        $listNodes = $dom->getElementById($parentId);
        $this->assertEquals(
            $innerHTML,
            $this->_parser
            ->getInnerHTML($listNodes->childNodes[$numb])
        );
    }

    /**
     * Provider of a basic HTML structure
     * There seems to be a problem that I'm not able to
     * identify the reason why it happens with the index
     * of elements when retrieving childNodes
     * the first element is 0 and the second is not 1 as
     * expected but 2.
     *
     * @return [type] [description]
     */
    public function htmlProvider()
    {
        $htmlDoc = <<<EOD
        <html>
          <body>
            <div id="parentDiv">
              <ul id="list">
                <li>Node1</li>
                <li>Node2</li>
                <li>Node3</li>
              </ul>
            </div>
          </body>
        </html>
EOD;

        return [
          [$htmlDoc, 'list', 'li', 0, 'Node1'],
          [$htmlDoc, 'list', 'li', 2, 'Node2'],
        ];
    }
}
