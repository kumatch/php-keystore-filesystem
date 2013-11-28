<?php
namespace Kumatch\KeyStore\Filesystem;

use Kumatch\Path;
use Kumatch\KeyStore\AccessDriverInterface;
use Kumatch\KeyStore\Exception\ErrorException;

class Driver implements AccessDriverInterface
{
    /** @var  string */
    protected $rootPath;
    /** @var  Access */
    protected $access;

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

        if (!$this->access()->isFile($filename)) {
            return null;
        }

        return $this->access()->get($filename);
    }

    /**
     * @param $key
     * @return bool
     */
    public function remove($key)
    {
        $filename = $this->createPath($key);

        if (!$this->access()->isFile($filename)) {
            return true;
        }

        if (!$this->access()->unlink($filename)) {
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
        return $this->access()->isFile($this->createPath($key));
    }

    /**
     * @param $key
     * @return bool
     */
    public function isNamespace($key)
    {
        return $this->access()->isDir($this->createPath($key));
    }


    /**
     * @return Access
     */
    protected function access()
    {
        if (!$this->access) {
            $this->access = new Access();
        }

        return $this->access;
    }

    /**
     * @param $filename
     * @param $content
     * @param bool $append
     * @return bool
     * @throws ErrorException
     */
    protected function writeToFile($filename, $content, $append = false)
    {
        $this->createDirectory(Path::dirname($filename));

        if ($this->access()->isDir($filename)) {
            throw new ErrorException();
        }

        $result = $this->access()->put($filename, $content, $append);
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
        if ($this->access()->exists($dirname)) {
            if (!$this->access()->isDir($dirname)) {
                throw new ErrorException();
            }
        } else {
            if (!@$this->access()->mkdir($dirname, 0755, true)) {
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
            $files = array_diff($this->access()->scandir($path), array('.','..'));

            if (count($files) > 0) {
                break;
            }

            @$this->access()->rmdir($path);
            $path = Path::dirname($path);
        }
    }
}