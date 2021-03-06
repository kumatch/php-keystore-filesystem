<?php
namespace Kumatch\Test\KeyStore\Filesystem;

use Kumatch\KeyStore\Filesystem\Driver;
use Kumatch\Fs\Temp\Temp;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /** @var  string */
    protected $dir;
    /** @var  string */
    protected $textFilename;
    /** @var  string */
    protected $binaryFilename;

    /** @var  string */
    protected $methodName;
    protected $secondArgument;

    protected function setUp()
    {
        parent::setUp();

        $temp = new Temp();

        $prefix = sprintf("%s-", strtolower( str_replace('\\', '-', get_called_class()) ));
        $this->dir = $temp->dir()->prefix($prefix)->create();

        $this->textFilename   = __DIR__ . "/sample.txt";
        $this->binaryFilename = __DIR__ . "/sample.png";
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    protected function loadTextFile()
    {
        $fh = fopen($this->textFilename, "r");
        $text = fread($fh, filesize($this->textFilename));
        fclose($fh);

        return $text;
    }

    protected function loadBinaryFile()
    {
        $fh = fopen($this->binaryFilename, "rb");
        $binary = fread($fh, filesize($this->binaryFilename));
        fclose($fh);

        return $binary;
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfPathIsTraversed()
    {
        $driver = new Driver($this->dir);

        $key = "../foo";
        if (!$this->secondArgument) {
            $this->secondArgument = $this->loadBinaryFile();
        }

        call_user_func_array(array($driver, $this->methodName), array($key, $this->secondArgument));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfKeyIsBlank()
    {
        $driver = new Driver($this->dir);

        $key = "";
        if (!$this->secondArgument) {
            $this->secondArgument = $this->loadBinaryFile();
        }


        call_user_func_array(array($driver, $this->methodName), array($key, $this->secondArgument));
    }

    /**
     * @test
     * @expectedException \Kumatch\KeyStore\Filesystem\Exception\ErrorException
     */
    public function throwExceptionIfKeyIsDotOnly()
    {
        $driver = new Driver($this->dir);

        $key = ".";
        if (!$this->secondArgument) {
            $this->secondArgument = $this->loadBinaryFile();
        }

        call_user_func_array(array($driver, $this->methodName), array($key, $this->secondArgument));
    }
}