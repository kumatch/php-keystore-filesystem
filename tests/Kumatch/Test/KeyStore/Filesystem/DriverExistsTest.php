<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverExistsTest extends TestCase
{
    protected $methodName = "exists";

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
    public function exists()
    {
        $driver = new Driver($this->dir);

        $key1 = "foo";
        $key2 = "bar";
        $key3 = "foo/bar";

        $path1 = Path::join($this->dir, $key1);
        $path2 = Path::join($this->dir, $key2);
        $path3 = Path::join($this->dir, $key3);

        $this->assertFalse($driver->exists($key1));
        $this->assertFalse($driver->exists($key2));
        $this->assertFalse($driver->exists($key3));

        touch($path1);

        $this->assertTrue($driver->exists($key1));
        $this->assertFalse($driver->exists($key2));
        $this->assertFalse($driver->exists($key3));

        touch($path2);

        $this->assertTrue($driver->exists($key1));
        $this->assertTrue($driver->exists($key2));
        $this->assertFalse($driver->exists($key3));

        unlink($path1);
        mkdir($path1, 0755, true);
        touch($path3);

        $this->assertFalse($driver->exists($key1));
        $this->assertTrue($driver->exists($key3));
    }
}