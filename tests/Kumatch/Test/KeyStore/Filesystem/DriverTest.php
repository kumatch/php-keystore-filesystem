<?php
namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;

class DriverTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\InvalidArgumentException
     */
    public function throwExceptionIfRootPathIsNotExists()
    {
        $driver = new Driver("/path/to/not_exists_diretory");
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\InvalidArgumentException
     */
    public function throwExceptionIfRootPathIsNotDirectory()
    {
        $driver = new Driver(__FILE__);
    }
}