<?php

namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Path;

class DriverCopyTest extends TestCase
{
    protected $methodName = "copy";
    protected $isExistsAfterInvoke = true;

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

        $src = "foo";
        $dst = "bar";

        $binary = $this->loadBinaryFile();

        $srcPath = Path::join($this->dir, $src);
        $dstPath = Path::join($this->dir, $dst);

        file_put_contents($srcPath, $binary);

        $this->assertFileExists($srcPath);
        $this->assertFileNotExists($dstPath);

        call_user_func_array(array($driver, $this->methodName), array($src, $dst));

        $this->assertFileExists($dstPath);
        $this->assertEquals($binary, file_get_contents($dstPath));

        $this->assertEquals($this->isExistsAfterInvoke, file_exists($srcPath));
        $this->assertEquals($this->isExistsAfterInvoke, is_file($srcPath));
    }

    /**
     * @test
     */
    public function runAndSuccessTwo()
    {
        $driver = new Driver($this->dir);

        $src = "abc";
        $dst = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $srcPath  = Path::join($this->dir, $src);
        $dstPath1 = Path::join($this->dir, "foo");
        $dstPath2 = Path::join($this->dir, "foo/bar");
        $dstPath3 = Path::join($this->dir, "foo/bar/baz");

        file_put_contents($srcPath, $binary);

        $this->assertFileExists($srcPath);
        $this->assertFileNotExists($dstPath1);
        $this->assertFileNotExists($dstPath2);
        $this->assertFileNotExists($dstPath3);

        call_user_func_array(array($driver, $this->methodName), array($src, $dst));

        $this->assertFileExists($dstPath1);
        $this->assertFileExists($dstPath2);
        $this->assertFileExists($dstPath3);

        $this->assertTrue(is_dir($dstPath1));
        $this->assertTrue(is_dir($dstPath2));
        $this->assertTrue(is_file($dstPath3));

        $this->assertEquals($binary, file_get_contents($dstPath3));

        $this->assertEquals($this->isExistsAfterInvoke, file_exists($srcPath));
        $this->assertEquals($this->isExistsAfterInvoke, is_file($srcPath));
    }

    /**
     * @test
     */
    public function shouldGetFalseIfSourceKeyIsNotExists()
    {
        $driver = new Driver($this->dir);

        $src = "foo";
        $dst = "bar";

        $dstPath  = Path::join($this->dir, $dst);

        $this->assertFalse(call_user_func_array(array($driver, $this->methodName), array($src, $dst)));
        $this->assertFileNotExists($dstPath);
    }

    /**
     * @test
     */
    public function shouldGetFalseIfSourceKeyIsNamespace()
    {
        $driver = new Driver($this->dir);

        $src = "foo";
        $dst = "bar";

        $srcPath  = Path::join($this->dir, $src);

        mkdir($srcPath, 0755, true);

        $this->assertFalse(call_user_func_array(array($driver, $this->methodName), array($src, $dst)));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfParentPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $src = "abc";
        $dst = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $srcPath  = Path::join($this->dir, $src);
        $dstPath = Path::join($this->dir, $dst);
        $dstParentPath = Path::join($this->dir, "foo");

        file_put_contents($srcPath, $binary);
        touch($dstParentPath);

        $this->assertFileExists($dstParentPath);
        $this->assertFileNotExists($dstPath);

        call_user_func_array(array($driver, $this->methodName), array($src, $dst));

        $this->assertFileNotExists($dstPath);

        $this->assertTrue(file_exists($srcPath));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfNestedPathFileIsExists()
    {
        $driver = new Driver($this->dir);

        $src = "abc";
        $dst = "foo";
        $binary = $this->loadBinaryFile();

        $srcPath = Path::join($this->dir, $src);
        $dstPath = Path::join($this->dir, $dst);
        $dstNestedPath = Path::join($this->dir, $dst, "bar/baz");

        file_put_contents($srcPath, $binary);

        mkdir(Path::dirname($dstNestedPath), 0755, true);
        touch($dstNestedPath);

        $this->assertFileExists($dstNestedPath);

        call_user_func_array(array($driver, $this->methodName), array($src, $dst));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfDirectoryIsExists()
    {
        $driver = new Driver($this->dir);

        $src = "abc";
        $dst = "foo/bar/baz";
        $binary = $this->loadBinaryFile();

        $srcPath = Path::join($this->dir, $src);
        $dstPath = Path::join($this->dir, $dst);

        file_put_contents($srcPath, $binary);
        mkdir($dstPath, 0755, true);

        $this->assertTrue(is_dir($dstPath));

        call_user_func_array(array($driver, $this->methodName), array($src, $dst));
    }
}