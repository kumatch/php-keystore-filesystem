<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverImportTest extends TestCase
{
    protected $methodName = "import";

    protected function setUp()
    {
        parent::setUp();

        $this->secondArgument = $this->binaryFilename;
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     =* @test
     */
    public function runAndSuccessOne()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $text = $this->loadTextFile();
        $binary = $this->loadBinaryFile();

        $path = Path::join($this->dir, $key);

        $this->assertFileNotExists($path);

        $importFilename = $this->textFilename;

        $driver->import($key, $importFilename);

        $this->assertFileExists($path);
        $this->assertEquals($text, file_get_contents($path));
        $this->assertNotEquals($binary, file_get_contents($path));

        $importFilename = $this->binaryFilename;

        $driver->import($key, $importFilename);

        $this->assertFileExists($path);
        $this->assertNotEquals($text, file_get_contents($path));
        $this->assertEquals($binary, file_get_contents($path));
    }

    /**
     * @test
     */
    public function runAndSuccessTwo()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $binary = $this->loadBinaryFile();
        $importFilename = $this->binaryFilename;

        $path1 = Path::join($this->dir, "foo");
        $path2 = Path::join($this->dir, "foo/bar");
        $path3 = Path::join($this->dir, "foo/bar/baz");

        $this->assertFileNotExists($path1);
        $this->assertFileNotExists($path2);
        $this->assertFileNotExists($path3);

        $driver->import($key, $importFilename);

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileExists($path3);

        $this->assertTrue(is_dir($path1));
        $this->assertTrue(is_dir($path2));
        $this->assertTrue(is_file($path3));

        $this->assertEquals($binary, file_get_contents($path3));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfParentPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $importFilename = $this->binaryFilename;

        $filename = Path::join($this->dir, "foo");

        touch($filename);

        $this->assertFileExists($filename);
        $this->assertTrue(is_file($filename));

        $driver->import($key, $importFilename);
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfNestedPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $importFilename = $this->binaryFilename;

        $path1 = Path::join($this->dir, "foo");
        $path2 = Path::join($this->dir, "foo/bar/baz");

        mkdir(Path::dirname($path2), 0755, true);
        touch($path2);

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertTrue(is_dir($path1));
        $this->assertTrue(is_file($path2));

        $driver->import($key, $importFilename);
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfDirectoryIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $importFilename = $this->binaryFilename;

        $directory = Path::join($this->dir, $key);
        mkdir($directory, 0755, true);

        $this->assertFileExists($directory);
        $this->assertTrue(is_dir($directory));

        $driver->import($key, $importFilename);
    }
}