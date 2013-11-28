<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverReadTest extends TestCase
{
    protected $methodName = "read";

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
     */
    public function readOne()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $string = "hello, world";
        $binary = $this->loadBinaryFile();

        $path = Path::join($this->dir, $key);

        $this->assertNull($driver->read($key));

        file_put_contents($path, $string);

        $this->assertEquals($string, $driver->read($key));
        $this->assertNotEquals($binary, $driver->read($key));

        file_put_contents($path, $binary);

        $this->assertNotEquals($string, $driver->read($key));
        $this->assertEquals($binary, $driver->read($key));
    }

    /**
     * @test
     */
    public function readTwo()
    {
        $driver = new Driver($this->dir);

        $key1 = "foo";
        $key2 = "foo/bar";
        $key3 = "foo/bar/baz";
        $string = "hello, world";

        $path1 = Path::join($this->dir, $key1);
        $path2 = Path::join($this->dir, $key2);
        $path3 = Path::join($this->dir, $key3);

        $this->assertNull($driver->read($key1));
        $this->assertNull($driver->read($key2));
        $this->assertNull($driver->read($key3));

        mkdir($path1, 0755, true);
        file_put_contents($path2, $string);

        $this->assertNull($driver->read($key1));
        $this->assertEquals($string, $driver->read($key2));
        $this->assertNull($driver->read($key3));
    }
}