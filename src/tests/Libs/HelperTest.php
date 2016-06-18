<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use UberCrawler\Libs\Helper as Helper;

class HelperTest extends TestCase
{
    protected $_root;

    public function setUp()
    {
        $this->_root = vfsStream::setup('testDir');
    }

    public function testPrintOut()
    {
        $message = 'test message';
        $type = 'INFO';
        $output = "${type}::: {$message} \n";
        $this->expectOutputString($output);
        Helper::printOut($message, $type);
    }

    public function testMakeDirs()
    {

        // Test 1 level deep
        $this->assertFalse($this->_root->hasChild('innerDir1'));
        Helper::makedirs(vfsStream::url('testDir/innerDir1'));
        $this->assertTrue($this->_root->hasChild('innerDir1'));

        // Test 2nd level
        $this->assertFalse($this->_root->hasChild('innerDir1/innerDir2'));
        Helper::makedirs(vfsStream::url('testDir/innerDir1/innerDir2'));
        $this->assertTrue($this->_root->hasChild('innerDir1/innerDir2'));

        // Test 3rd Level & Permissions
        $permission = 0644;
        $this->assertFalse($this->_root->hasChild('tDir1/tDir2/tDir3'));
        Helper::makedirs(vfsStream::url('testDir/tDir1/tDir2/tDir3'), 
                         $permission);
        
        $this->assertTrue($this->_root->hasChild('tDir1/tDir2/tDir3'));
        $this->assertEquals(0644, $this->_root
                                       ->getChild('tDir1/tDir2/tDir3')
                                       ->getPermissions());
    }
}
