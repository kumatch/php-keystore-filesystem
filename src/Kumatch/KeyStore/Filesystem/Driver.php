<?php
namespace Kumatch\KeyStore\Filesystem;

use Kumatch\Path;
use Kumatch\KeyStore\AccessDriverInterface;
use Kumatch\KeyStore\Exception\ErrorException;

class Driver implements AccessDriverInterface
{
    protected $rootPath;

    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function write($key, $value)
    {
        $filename = $this->createPath($key);
        $append = false;

        return $this->writeToFile($filename, $value, $append);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function append($key, $value)
    {
        $filename = $this->createPath($key);
        $append = true;

        return $this->writeToFile($filename, $value, $append);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function read($key)
    {
        $filename = $this->createPath($key);

        if (is_file($filename)) {
            return file_get_contents($filename);
        } else {
            return null;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        $filename = $this->createPath($key);

        if (!is_file($filename)) {
            return true;
        }

        if (!unlink($filename)) {
            return false;
        }

        $this->removeParents(Path::dirname($filename));

        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        $filename = $this->createPath($key);

        return is_file($filename);
    }

    /**
     * @param $key
     * @return bool
     */
    public function isNamespace($key)
    {
        $filename = $this->createPath($key);

        return is_dir($filename);
    }


    /**
     * @param $filename
     * @param $value
     * @param bool $append
     * @return bool
     * @throws ErrorException
     */
    protected function writeToFile($filename, $value, $append = false)
    {
        $this->createDirectory(Path::dirname($filename));

        if (is_dir($filename)) {
            throw new ErrorException();
        }

        if ($append) {
            $result = file_put_contents($filename, $value, FILE_APPEND);
        } else {
            $result = file_put_contents($filename, $value);
        }

        if ($result === false) {
            throw new ErrorException();
        }

        return true;
    }

    /**
     * @param $key
     * @throws ErrorException
     * @return mixed
     */
    protected function createPath($key)
    {
        $path = preg_replace('![/]+$!', '', Path::join($this->rootPath, $key));

        if (!$this->isFollowedRootPath($path)) {
            throw new ErrorException();
        }

        return $path;
    }

    /**
     * @param $dirname
     * @throws ErrorException
     */
    protected function createDirectory($dirname)
    {
        if (file_exists($dirname)) {
            if (!is_dir($dirname)) {
                throw new ErrorException();
            }
        } else {
            if (!@mkdir($dirname, 0755, true)) {
                throw new ErrorException();
            }
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isFollowedRootPath($path)
    {
        return ($path !== $this->rootPath)
            && (strpos($path, $this->rootPath) !== false);
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function removeParents($path)
    {
        while ($this->isFollowedRootPath($path)) {
            $files = array_diff(scandir($path), array('.','..'));

            if (count($files) > 0) {
                break;
            }

            @rmdir($path);
            $path = Path::dirname($path);
        }
    }
}