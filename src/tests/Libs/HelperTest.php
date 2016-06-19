<?php

namespace UberCrawler\Tests\Libs;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use UberCrawler\Config\App as App;
use UberCrawler\Libs\Helper as Helper;
use UberCrawler\Libs\Exceptions\GeneralException as GeneralException;

class HelperTest extends TestCase
{
    protected $_root;

    /**
     * Using vfsStream to mock the file system in order to test
     * folder/directory creation/writing
     */
    public function setUp()
    {
        $this->_root = vfsStream::setup('testDir');
    }

    /**
     * Test that the printOut() method actually
     * outputs the information in the proper format
     */
    public function testPrintOut()
    {
        $message = 'test message';
        $type = 'INFO';
        $output = "${type}::: {$message} \n";
        $this->expectOutputString($output);
        Helper::printOut($message, $type);
    }

    /**
     * Test the directory making method and its recursiveness
     * as well as its capacity to create nested folders
     */
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
        Helper::makedirs(
            vfsStream::url('testDir/tDir1/tDir2/tDir3'),
            $permission
        );
        
        $this->assertTrue($this->_root->hasChild('tDir1/tDir2/tDir3'));
        $this->assertEquals(0644, $this->_root
                                       ->getChild('tDir1/tDir2/tDir3')
                                       ->getPermissions());
    }

    /**
     * Test this method's capacity to create the necessary
     * directory and input the appropriate content into the
     * file (usually HTML content)
     *
     * @covers UberCrawler\Libs\Helper::storeIntoFile
     * @dataProvider storeIntoFileDataProvider
     */
    public function teststoreIntoFile(
        $data,
        $pageNumb,
        $expected
    ) {
        // Handle multiple expected results
        // Success | Failure | Exception
        switch ($expected) {
            case true:
                $status = Helper::storeIntoFile($data, $pageNumb);
                $this->assertTrue($status['success']);
                // Verify the file's content
                $fileContent = file_get_contents($status['fileName']);
                $this->assertEquals($data, $fileContent);
                // Remove the file
                unlink($status['fileName']);
                break;
            case false:
                // Expect an exception
                $this->expectException(GeneralException::class);
                $status = Helper::storeIntoFile($data, $pageNumb);
                break;
            default:
                $status = Helper::storeIntoFile($data, $pageNumb);
                $this->assertEquals($expected, $status['success']);
                break;
        }
    }

    public function storeIntoFileDataProvider()
    {
        return [
            ['testcontent', 100, true],
            ['testcontent', '', false],
            ['testcontent', 'abc', false]
        ];
    }

    /**
     * @covers UberCrawler\Libs\Helper::buildStorageFilePath
     * @dataProvider buildStorageFilePathProvider
     */
    public function testbuildStorageFilePath(
        $pageNumb,
        $expectedFileName
    ) {
        $dataStorageDir = App::$APP_SETTINGS['data_storage_dir'];
        $expectedPath = implode(
            DIRECTORY_SEPARATOR,
            [$dataStorageDir,
            $expectedFileName]
        );
        $output = Helper::buildStorageFilePath($pageNumb);
        $this->assertEquals($expectedPath, $output);
    }

    public function buildStorageFilePathProvider()
    {
        return [
            [100, 'data-page_100.html'],
            [1, 'data-page_1.html']
        ];
    }
}
