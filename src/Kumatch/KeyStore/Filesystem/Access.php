<?php
namespace Kumatch\KeyStore\Filesystem;

class Access
{
    /**
     * @param $filename
     * @return bool
     */
    public function exists($filename)
    {
        return file_exists($filename);
    }

    /**
     * @param $filename
     * @return bool
     */
    public function isFile($filename)
    {
        return is_file($filename);
    }

    /**
     * @param $filename
     * @return bool
     */
    public function isDir($filename)
    {
        return is_dir($filename);
    }

    /**
     * @param $filename
     * @return mixed
     */
    public function get($filename)
    {
        return file_get_contents($filename);
    }

    /**
     * @param $filename
     * @param $content
     * @param bool $append
     * @return int
     */
    public function put($filename, $content, $append = false)
    {
        if ($append) {
            return file_put_contents($filename, $content, FILE_APPEND);
        } else {
            return file_put_contents($filename, $content);
        }
    }

    /**
     * @param $filename
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public function mkdir($filename, $mode = 0700, $recursive = true)
    {
        return mkdir($filename, $mode, $recursive);
    }

    /**
     * @param $dirname
     * @return array
     */
    public function scandir($dirname)
    {
        return scandir($dirname);
    }

    /**
     * @param $filename
     * @return bool
     */
    public function unlink($filename)
    {
        return unlink($filename);
    }

    /**
     * @param $filename
     * @return bool
     */
    public function rmdir($filename)
    {
        return rmdir($filename);
    }
}