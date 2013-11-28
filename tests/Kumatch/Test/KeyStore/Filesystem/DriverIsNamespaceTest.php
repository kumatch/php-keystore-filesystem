<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverIsNamespaceTest extends TestCase
{
    protected $methodName = "isNamespace";

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
    public function isNamespace()
    {
        $driver = new Driver($this->dir);

        $key1 = "foo";
        $key2 = "foo/bar";
        $key3 = "foo/bar/baz";

        $path1 = Path::join($this->dir, $key1);
        $path2 = Path::join($this->dir, $key2);

        $this->assertFalse($driver->isNamespace($key1));
        $this->assertFalse($driver->isNamespace($key2));
        $this->assertFalse($driver->isNamespace($key3));

        mkdir($path1, 0755, true);
        touch($path2);

        $this->assertTrue($driver->isNamespace($key1));
        $this->assertFalse($driver->isNamespace($key2));
        $this->assertFalse($driver->isNamespace($key3));
    }
}