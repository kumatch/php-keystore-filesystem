<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverExportTest extends TestCase
{
    protected $methodName = "export";

    protected function setUp()
    {
        parent::setUp();

        $this->secondArgument = Path::join($this->dir, "foo.txt");
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function exportOne()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $binary = $this->loadBinaryFile();

        $exportFilename = Path::join($this->dir, "ok.bin");

        $this->assertFileNotExists($exportFilename);

        $path = Path::join($this->dir, $key);

        file_put_contents($path, $binary);

        $driver->export($key, $exportFilename);

        $this->assertFileExists($exportFilename);
        $this->assertEquals($binary, file_get_contents($exportFilename));
    }

    /**
     * @test
     */
    public function exportTwo()
    {
        $driver = new Driver($this->dir);

        $key = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $exportFilename = Path::join($this->dir, "path/to/ok.bin");

        $this->assertFileNotExists($exportFilename);

        $path = Path::join($this->dir, $key);

        mkdir(dirname($path), 0755, true);
        file_put_contents($path, $binary);

        $driver->export($key, $exportFilename);

        $this->assertFileExists($exportFilename);
        $this->assertEquals($binary, file_get_contents($exportFilename));
    }

    /**
     * @test
     */
    public function exportZeroFile()
    {
        $driver = new Driver($this->dir);

        $key = "foo";

        $exportFilename = Path::join($this->dir, "zero.txt");

        $this->assertFileNotExists($exportFilename);

        $driver->export($key, $exportFilename);

        $this->assertFileExists($exportFilename);
        $this->assertEquals(0, filesize($exportFilename));
    }

    /**
     * @test
     */
    public function dontThrowExceptionIfExportsPathIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $binary = $this->loadBinaryFile();
        $beforeValue = "before value.";

        $path = Path::join($this->dir, "foo");
        $exportFilename = Path::join($this->dir, "bar");

        file_put_contents($path, $binary);
        file_put_contents($exportFilename, $beforeValue);

        $driver->export($key, $exportFilename);

        $this->assertNotEquals($beforeValue, file_get_contents($exportFilename));
        $this->assertEquals($binary, file_get_contents($exportFilename));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\InvalidArgumentException
     */
    public function throwExceptionIfExportsPathIsDirectory()
    {
        $driver = new Driver($this->dir);

        $key = "foo";

        $exportFilename = Path::join($this->dir, "bar/baz");

         mkdir($exportFilename, 0755, true);

        $driver->export($key, $exportFilename);
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfExportsParentPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $key = "foo";
        $exportFilename = Path::join($this->dir, "aaa/bbb");

        file_put_contents(Path::join($this->dir, $key), "foo");
        touch(dirname($exportFilename));

        $driver->export($key, $exportFilename);
    }
}