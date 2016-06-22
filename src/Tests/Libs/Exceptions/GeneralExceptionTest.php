<?php

namespace UberCrawler\Tests\Libs\Exceptions;

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class GeneralExceptionTest extends TestCase
{
    public function testtoString()
    {
        $output = "UberCrawler\Libs\Exceptions\GeneralException: ".
              "[FATAL]::: test message\n";

        $exception = new GeneralException(
            'test message',
            'FATAL',
            0
        );

        $this->assertEquals($output, $exception->__toString());
    }
}
