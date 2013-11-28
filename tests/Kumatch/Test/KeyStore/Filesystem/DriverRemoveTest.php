<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverRemoveTest extends TestCase
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
    public function removeOne()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $binary = $this->loadBinaryFile();

        $path = Path::join($this->dir, $key);

        $this->assertTrue($driver->remove($key));

        file_put_contents($path, $binary);

        $this->assertFileExists($path);

        $this->assertTrue($driver->remove($key));

        $this->assertFileNotExists($path);
    }

    /**
     * @test
     */
    public function removeTwo()
    {
        $driver = new Driver($this->dir);

        $key1 = "foo";
        $key2 = "foo/bar";
        $key3 = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $path1 = Path::join($this->dir, $key1);
        $path2 = Path::join($this->dir, $key2);
        $path3 = Path::join($this->dir, $key3);

        $this->assertTrue($driver->remove($key1));
        $this->assertTrue($driver->remove($key2));
        $this->assertTrue($driver->remove($key3));


        mkdir($path1, 0755, true);
        file_put_contents($path2, $binary);

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileNotExists($path3);


        $this->assertTrue($driver->remove($key3));

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileNotExists($path3);


        $this->assertTrue($driver->remove($key1));

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileNotExists($path3);


        $this->assertTrue($driver->remove($key2));

        $this->assertFileNotExists($path1);
        $this->assertFileNotExists($path2);
        $this->assertFileNotExists($path3);
    }
}