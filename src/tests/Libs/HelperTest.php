<?php

use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Helper as Helper;

class HelperTest extends TestCase {


  public function testPrintOut() {

    $message = "test message";
    $type = "INFO";
    $output = "${type}::: {$message} \n";
    $this->expectOutputString($output);
    Helper::printOut($message, $type);

  }


  public function testMakeDirs() {

    

  }

}