<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverWriteTest extends TestCase
{
    protected $methodName = "write";

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
    public function runAndSuccessOne()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $string = "hello, world";
        $binary = $this->loadBinaryFile();

        $path = Path::join($this->dir, $key);

        $this->assertFileNotExists($path);

        call_user_func_array(array($driver, $this->methodName), array($key, $string));

        $this->assertFileExists($path);
        $this->assertEquals($string,    file_get_contents($path));
        $this->assertNotEquals($binary, file_get_contents($path));

        unlink($path);

        call_user_func_array(array($driver, $this->methodName), array($key, $binary));

        $this->assertFileExists($path);
        $this->assertNotEquals($string, file_get_contents($path));
        $this->assertEquals($binary,    file_get_contents($path));
    }

    /**
     * @test
     */
    public function runAndSuccessTwo()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $string = "hello, world";
        $binary = $this->loadBinaryFile();

        $path1 = Path::join($this->dir, "foo");
        $path2 = Path::join($this->dir, "foo/bar");
        $path3 = Path::join($this->dir, "foo/bar/baz");

        $this->assertFileNotExists($path1);
        $this->assertFileNotExists($path2);
        $this->assertFileNotExists($path3);

        call_user_func_array(array($driver, $this->methodName), array($key, $string));

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileExists($path3);

        $this->assertTrue(is_dir($path1));
        $this->assertTrue(is_dir($path2));
        $this->assertTrue(is_file($path3));

        $this->assertEquals($string,    file_get_contents($path3));
        $this->assertNotEquals($binary, file_get_contents($path3));

        unlink($path3);

        call_user_func_array(array($driver, $this->methodName), array($key, $binary));

        $this->assertNotEquals($string, file_get_contents($path3));
        $this->assertEquals($binary,    file_get_contents($path3));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\ErrorException
     */
    public function throwExceptionIfParentPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $filename = Path::join($this->dir, "foo");

        touch($filename);

        $this->assertFileExists($filename);
        $this->assertTrue(is_file($filename));

        call_user_func_array(array($driver, $this->methodName), array($key, $binary));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\ErrorException
     */
    public function throwExceptionIfNestedPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $binary = $this->loadBinaryFile();

        $path1 = Path::join($this->dir, "foo");
        $path2 = Path::join($this->dir, "foo/bar/baz");

        mkdir(Path::dirname($path2), 0755, true);
        touch($path2);

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertTrue(is_dir($path1));
        $this->assertTrue(is_file($path2));

        call_user_func_array(array($driver, $this->methodName), array($key, $binary));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Exception\ErrorException
     */
    public function throwExceptionIfDirectoryIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $directory = Path::join($this->dir, $key);
        mkdir($directory, 0755, true);

        $this->assertFileExists($directory);
        $this->assertTrue(is_dir($directory));

        call_user_func_array(array($driver, $this->methodName), array($key, $binary));
    }
}