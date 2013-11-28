<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverAppendTest extends TestCase
{
    protected $methodName = "append";


    /**
     * @test
     */
    public function appendString()
    {
        $key = "foo/bar/baz";
        $path = Path::join($this->dir, $key);

        mkdir(Path::dirname($path), 0755, true);
        file_put_contents($path, "OK");

        $driver = new Driver($this->dir);
        $driver->append($key, "成功");

        $this->assertEquals("OK成功", file_get_contents($path));
    }

    public function appendBinary()
    {
        $key = "foo/bar/baz";
        $path = Path::join($this->dir, $key);
        $binary = $this->loadBinaryFile();

        mkdir(Path::dirname($path), 0755, true);
        file_put_contents($path, "あいうえお");

        $driver = new Driver($this->dir);
        $driver->append($key, $binary);

        $this->assertEquals("あいうえお" . $binary, file_get_contents($path));
    }
}