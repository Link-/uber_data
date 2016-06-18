<?php

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Parser as Parser;
use UberCrawler\Libs\Crawler as Crawler;
use UberCrawler\Libs\TripsCollection as TripsCollection;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class CrawlerTest extends TestCase
{
    protected $_crawler;

    public function setUp()
    {
        $this->_crawler = new Crawler();
    }

    public function tearDown()
    {
        $this->_crawler = null;
    }

    public function testgetLoginURL()
    {
        $this->assertFalse(empty($this->_crawler->getLoginURL()));
    }

    /**
     * @dataProvider validURLProvider
     * @covers UberCrawler\Libs\Crawler::setLoginURL
     */
    public function testsetLoginURLSuccess(
        $url,
        $expected
    ) {
        $this->_crawler->setLoginURL($url);
        $this->assertEquals(
            $expected,
            ($this->_crawler->getLoginURL() == $url)
        );
    }

    /**
     * @dataProvider invalidURLProvider
     * @covers UberCrawler\Libs\Crawler::setLoginURL
     */
    public function testsetLoginURLFailure($url)
    {
        $this->expectException(GeneralException::class);
        $this->_crawler->setLoginURL($url);
    }

    /**
     * @dataProvider validURLProvider
     * @covers UberCrawler\Libs\Crawler::setTripsURL
     */
    public function testsetTripsURLSuccess(
        $url,
        $expected
    ) {
        $this->_crawler->setTripsURL($url);
        $this->assertEquals(
            $expected,
            ($this->_crawler->getTripsURL() == $url)
        );
    }

    /**
     * @dataProvider invalidURLProvider
     * @covers UberCrawler\Libs\Crawler::setTripsURL
     */
    public function testsetTripsURLFailure($url)
    {
        $this->expectException(GeneralException::class);
        $this->_crawler->setTripsURL($url);
    }

    public function validURLProvider()
    {
        return [
            ['https://login.uber.com/login', true],
            ['http://login.uber.com/login', true],
        ];
    }

    public function invalidURLProvider()
    {
        return [
            [''],
            ['login'],
            ['login.uber.com'],
            ['www.login.uber.com'],
            ['1234'],
            [1234],
        ];
    }

    public function testgetTripsURL()
    {
        $this->assertFalse(empty($this->_crawler->getTripsURL()));
    }

    public function testgetParser()
    {
        $this->assertInstanceOf(
            Parser::class,
            $this->_crawler->getParser()
        );
    }

    public function testgetTripsCollection()
    {
        $this->assertInstanceOf(
            TripsCollection::class,
            $this->_crawler->getTripsCollection()
        );
    }

    /**
     * @dataProvider getCSRFTokenProvider
     * @covers UberCrawler\Libs\Crawler::getCSRFToken
     */
    public function testgetCSRFToken(
        $html,
        $expected
    ) {
        $method = self::getMethod('getCSRFToken');
        $output = $method->invokeArgs($this->_crawler, array($html));
        $this->assertEquals($expected, $output);
    }


    public function getCSRFTokenProvider()
    {
        // Read the HTML from a sample login file
        $file = __DIR__
        .DIRECTORY_SEPARATOR.
        '../_sample_data/login_sample.html';

        $goodHTML = file_get_contents($file);

        $corruptHTML = <<<EOD
        <html><body><div>Test</div></body></html>
EOD;

        return [
          [$goodHTML, '1466290348-01-lGJBTbT9pmNL-'.
            'GLSMXXFpMEzb8IY5u7B9AEnCjBslFM='],
          [$corruptHTML, ''],
        ];
    }

    public function teststoreIntoFile() {
        
    }

    /**
     * Using Reflection to test protected methods.
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     */
    protected static function getMethod($name)
    {
        $class = new ReflectionClass('UberCrawler\Libs\Crawler');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
